<?php
$paths = [
    'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
    'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
    'C:\\Program Files\\MariaDB 10.4\\bin\\mysqldump.exe',
    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
];
foreach ($paths as $p) {
    if (file_exists($p)) echo $p . PHP_EOL;
}
