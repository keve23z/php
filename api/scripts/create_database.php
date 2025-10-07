<?php
$host = '127.0.0.1';
$port = 3306;
$dbname = null; // connecting without db
$user = 'root';
$pass = '';
$targetDb = 'QL_KhachSan';
try {
    $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$targetDb}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "Database {$targetDb} created or already exists\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
