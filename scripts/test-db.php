<?php

// Simple DB connectivity test and create DB if missing.
// This script reads the project's .env file to obtain DB credentials
// and attempts to connect using PDO. If the DB doesn't exist it will
// attempt to create it.

function parseEnv($path)
{
    if (! file_exists($path)) {
        echo ".env file not found at: $path\n";
        exit(1);
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $items = [];
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        if (! str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $items[trim($key)] = trim($value, " \t\n\r\0\x0B\"");
    }

    return $items;
}

$env = parseEnv(__DIR__ . '/../.env');

$host = $env['DB_HOST'] ?? '127.0.0.1';
$port = $env['DB_PORT'] ?? '3306';
$db = $env['DB_DATABASE'] ?? 'hrm-system';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';

echo "Testing DB connection to {$host}:{$port} (database: {$db}) as user {$user}\n";

$dsn = "mysql:host={$host};port={$port}";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connected to MySQL server OK.\n";

    $stmt = $pdo->query("SHOW DATABASES LIKE '" . addslashes($db) . "'");
    $exists = $stmt->fetch(PDO::FETCH_NUM);
    if (! $exists) {
        echo "Database '$db' not found. Attempting to create...\n";
        $pdo->exec("CREATE DATABASE `" . str_replace('`', '``', $db) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        echo "Database created: $db\n";
    } else {
        echo "Database '$db' already exists.\n";
    }
} catch (PDOException $e) {
    echo "PDO connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Done. You can now run 'php artisan migrate --seed' to apply migrations and seeders.\n";
