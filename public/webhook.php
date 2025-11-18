<?php

/**
 * GitHub Webhook Auto Deploy Script for Laravel
 * Place this in /var/www/laravel-app/webhook.php
 */

// Configuration
$config = [
    'secret' => 'aku-suka-rama-rudi', // Ganti dengan secret key yang kuat
    'project_path' => '/var/www/peminjaman-ruangan',
    'log_file' => '/var/www/peminjaman-ruangan/storage/logs/deploy.log',
    'branch' => 'main'
];

// Handle Webhook
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    // Verify Secret Key
    $githubSecret = isset($_SERVER['HTTP_X_HUB_SIGNATURE_256']) ? $_SERVER['HTTP_X_HUB_SIGNATURE_256'] : '';
    $payload = file_get_contents('php://input');
    
    if (!$this->verifySignature($payload, $githubSecret, $config['secret'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid signature']);
        exit;
    }
    
    // Execute Deployment
    $result = $this->deploy($config);
    echo json_encode($result);
    
} else {
    // Show info page for GET requests
    echo "<h1>🚀 Laravel Auto Deploy Webhook</h1>";
    echo "<p><strong>URL:</strong> https://your-domain.com/webhook.php</p>";
    echo "<p><strong>Secret:</strong> " . $config['secret'] . "</p>";
    echo "<p><strong>Method:</strong> POST</p>";
    echo "<p><strong>Content-Type:</strong> application/json</p>";
    echo "<hr>";
    echo "<h3>GitHub Webhook Setup:</h3>";
    echo "<ol>";
    echo "<li>Go to your GitHub repository → Settings → Webhooks</li>";
    echo "<li>Add webhook URL</li>";
    echo "<li>Set Content type to <code>application/json</code></li>";
    echo "<li>Add secret: <code>" . $config['secret'] . "</code></li>";
    echo "<li>Select events: 'Just the push event'</li>";
    echo "</ol>";
}

function deploy($config) {
    $log = function($message) use ($config) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}\n";
        file_put_contents($config['log_file'], $logMessage, FILE_APPEND | LOCK_EX);
        echo $logMessage;
        flush();
    };

    try {
        $log('🎯 Deployment triggered by webhook');
        
        // 1. Git Pull
        $log('📥 Pulling latest changes...');
        $this->executeCommand("cd {$config['project_path']} && git fetch origin");
        $this->executeCommand("cd {$config['project_path']} && git reset --hard origin/{$config['branch']}");
        
        // 2. Composer Install
        $log('📦 Installing dependencies...');
        $this->executeCommand("cd {$config['project_path']} && composer install --no-dev --optimize-autoloader");
        
        // 3. Clear Cache
        $log('🗑️ Clearing cache...');
        $this->executeCommand("cd {$config['project_path']} && php artisan cache:clear");
        $this->executeCommand("cd {$config['project_path']} && php artisan config:clear");
        $this->executeCommand("cd {$config['project_path']} && php artisan view:clear");
        $this->executeCommand("cd {$config['project_path']} && php artisan route:clear");
        
        // 4. Database Migrations
        $log('📊 Running migrations...');
        $this->executeCommand("cd {$config['project_path']} && php artisan migrate --force");
        
        // 5. Set Permissions
        $log('🔐 Setting permissions...');
        $this->executeCommand("chmod -R 755 {$config['project_path']}/storage");
        $this->executeCommand("chmod -R 755 {$config['project_path']}/bootstrap/cache");
        $this->executeCommand("chown -R www-data:www-data {$config['project_path']}/storage");
        $this->executeCommand("chown -R www-data:www-data {$config['project_path']}/bootstrap/cache");
        
        // 6. Optimize
        $log('⚡ Optimizing application...');
        $this->executeCommand("cd {$config['project_path']} && php artisan config:cache");
        $this->executeCommand("cd {$config['project_path']} && php artisan route:cache");
        $this->executeCommand("cd {$config['project_path']} && php artisan view:cache");
        
        // 7. Restart PHP-FPM
        $log('🔄 Restarting PHP-FPM...');
        $this->executeCommand("sudo systemctl restart php8.1-fpm");
        
        $log('✅ Deployment completed successfully!');
        
        return [
            'success' => true,
            'message' => 'Deployment completed successfully',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
    } catch (Exception $e) {
        $log('❌ Deployment failed: ' . $e->getMessage());
        
        return [
            'success' => false,
            'message' => $e->getMessage(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
}

function executeCommand($command) {
    $output = [];
    $returnCode = 0;
    
    exec($command . ' 2>&1', $output, $returnCode);
    
    if ($returnCode !== 0) {
        throw new Exception("Command failed: {$command}\nOutput: " . implode("\n", $output));
    }
    
    return $output;
}

function verifySignature($payload, $githubSignature, $secret) {
    if (empty($githubSignature)) {
        return false;
    }
    
    $calculatedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);
    return hash_equals($calculatedSignature, $githubSignature);
}