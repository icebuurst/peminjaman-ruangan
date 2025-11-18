<?php

/**
 * GitHub Webhook Auto Deploy Script for Laravel
 * Cloudflare Compatible Version
 */

// Configuration
$config = [
    'secret' => 'aku-suka-rama-rudi', // GANTI DkNGAN SECRET KEY MU
    'project_path' => '/var/www/manajemen-ruangan', // GANTI DENGAN PATH PROJECT MU
    'log_file' => '/var/www/manajemen-ruanagan/storage/logs/deploy.log',
    'branch' => 'main',
    'allow_get_test' => true // Set false di production
];

// Start output buffering
ob_start();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handleWebhook($config);
    } else {
        showInfoPage($config);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

function handleWebhook($config) {
    header('Content-Type: application/json');
    
    // Get payload and signature
    $payload = file_get_contents('php://input');
    $githubSignature = isset($_SERVER['HTTP_X_HUB_SIGNATURE_256']) ? 
        $_SERVER['HTTP_X_HUB_SIGNATURE_256'] : '';
    
    // Log the request for debugging
    logMessage("📨 Webhook received", $config);
    logMessage("Signature: " . $githubSignature, $config);
    logMessage("Payload length: " . strlen($payload), $config);
    
    // Verify signature
    if (!verifySignature($payload, $githubSignature, $config['secret'])) {
        logMessage("❌ Invalid signature", $config);
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid signature']);
        return;
    }
    
    // Verify it's a push to the correct branch
    $data = json_decode($payload, true);
    if (isset($data['ref']) && $data['ref'] === 'refs/heads/' . $config['branch']) {
        logMessage("✅ Valid push to " . $config['branch'], $config);
        $result = deploy($config);
        echo json_encode($result);
    } else {
        logMessage("⚠️ Ignoring non-push event or wrong branch", $config);
        echo json_encode(['success' => true, 'message' => 'Not a push to target branch, ignored']);
    }
}

function deploy($config) {
    $log = function($message) use ($config) {
        logMessage($message, $config);
    };

    try {
        $log('🎯 Starting deployment...');
        
        // 1. Git Pull
        $log('📥 Pulling latest changes...');
        executeCommand("cd {$config['project_path']} && git fetch origin", $config);
        executeCommand("cd {$config['project_path']} && git reset --hard origin/{$config['branch']}", $config);
        
        // 2. Composer Install
        $log('📦 Installing dependencies...');
        executeCommand("cd {$config['project_path']} && composer install --no-dev --optimize-autoloader", $config);
        
        // 3. Clear Cache
        $log('🗑️ Clearing cache...');
        executeCommand("cd {$config['project_path']} && php artisan cache:clear", $config);
        executeCommand("cd {$config['project_path']} && php artisan config:clear", $config);
        executeCommand("cd {$config['project_path']} && php artisan view:clear", $config);
        executeCommand("cd {$config['project_path']} && php artisan route:clear", $config);
        
        // 4. Database Migrations
        $log('📊 Running migrations...');
        executeCommand("cd {$config['project_path']} && php artisan migrate --force", $config);
        
        // 5. Set Permissions
        $log('🔐 Setting permissions...');
        executeCommand("chmod -R 755 {$config['project_path']}/storage", $config);
        executeCommand("chmod -R 755 {$config['project_path']}/bootstrap/cache", $config);
        executeCommand("chown -R www-data:www-data {$config['project_path']}/storage", $config);
        executeCommand("chown -R www-data:www-data {$config['project_path']}/bootstrap/cache", $config);
        
        // 6. Optimize
        $log('⚡ Optimizing application...');
        executeCommand("cd {$config['project_path']} && php artisan config:cache", $config);
        executeCommand("cd {$config['project_path']} && php artisan route:cache", $config);
        executeCommand("cd {$config['project_path']} && php artisan view:cache", $config);
        
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

function executeCommand($command, $config) {
    $output = [];
    $returnCode = 0;
    
    exec($command . ' 2>&1', $output, $returnCode);
    
    $outputStr = implode("\n", $output);
    logMessage("Command: {$command}\nOutput: {$outputStr}", $config);
    
    if ($returnCode !== 0) {
        throw new Exception("Command failed: {$command}\nOutput: {$outputStr}");
    }
    
    return $output;
}

function verifySignature($payload, $githubSignature, $secret) {
    if (empty($githubSignature) || empty($secret)) {
        return false;
    }
    
    $calculatedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);
    return hash_equals($calculatedSignature, $githubSignature);
}

function logMessage($message, $config) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}\n";
    
    // Log to file
    file_put_contents($config['log_file'], $logMessage, FILE_APPEND | LOCK_EX);
    
    // Also output for webhook response
    echo $logMessage;
    ob_flush();
    flush();
}

function showInfoPage($config) {
    echo "<h1>🚀 Laravel Auto Deploy Webhook</h1>";
    echo "<p><strong>Status:</strong> ✅ Active</p>";
    echo "<p><strong>Project Path:</strong> " . $config['project_path'] . "</p>";
    echo "<p><strong>Branch:</strong> " . $config['branch'] . "</p>";
    echo "<hr>";
    echo "<h3>GitHub Webhook Configuration:</h3>";
    echo "<ul>";
    echo "<li><strong>Payload URL:</strong> https://your-domain.com/webhook.php</li>";
    echo "<li><strong>Content type:</strong> application/json</li>";
    echo "<li><strong>Secret:</strong> " . $config['secret'] . "</li>";
    echo "<li><strong>Events:</strong> Just the push event</li>";
    echo "</ul>";
    echo "<hr>";
    echo "<p><a href='/webhook.php?test=1'>Test Deployment</a> (GET request for testing)</p>";
}