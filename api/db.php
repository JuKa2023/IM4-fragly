<?php

// config.php
$host = getenv('MYSQL_HOST');
$db = getenv('MYSQL_DATABASE');
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASSWORD');

// Debug environment variables
error_log("Database connection attempt - Host: " . (getenv('MYSQL_HOST') ? 'set' : 'not set'));
error_log("Database connection attempt - Database: " . (getenv('MYSQL_DATABASE') ? 'set' : 'not set'));
error_log("Database connection attempt - User: " . (getenv('MYSQL_USER') ? 'set' : 'not set'));
error_log("Database connection attempt - Password: " . (getenv('MYSQL_PASSWORD') ? 'set' : 'not set'));

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    // Optional: Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection error: ' . $e->getMessage()
    ]);
    exit;
}
