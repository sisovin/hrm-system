<?php

// Usage: php create-db-user.php <adminUser> <adminPassword> <database> <newUser> <newPassword> [host] [port]

if ($argc < 6) {
    echo "Usage: php create-db-user.php <adminUser> <adminPassword> <database> <newUser> <newPassword> [host] [port]\n";
    exit(1);
}

$adminUser = $argv[1];
$adminPass = $argv[2];
$database = $argv[3];
$newUser = $argv[4];
$newPass = $argv[5];
$host = $argv[6] ?? '127.0.0.1';
$port = $argv[7] ?? '3306';

$dsn = "mysql:host={$host};port={$port}";
try {
    $pdo = new PDO($dsn, $adminUser, $adminPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connected as admin {$adminUser}\n";
    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `".str_replace('`', '``', $database)."` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "Database `{$database}` created or already exists.\n";
    // Create user and grant privileges
    $pdo->exec("CREATE USER IF NOT EXISTS '{$newUser}'@'{$host}' IDENTIFIED BY '{$newPass}';");
    $pdo->exec("GRANT ALL PRIVILEGES ON `{$database}`.* TO '{$newUser}'@'{$host}';");
    $pdo->exec('FLUSH PRIVILEGES;');
    echo "Created user '{$newUser}'@'{$host}' and granted privileges for DB '{$database}'.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Done. Update your .env to use DB_USERNAME={$newUser} and DB_PASSWORD={$newPass}, then run php artisan migrate --seed.\n";
