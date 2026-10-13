<?php
/**
 * Database connection — edit these 4 values to match your setup
 * XAMPP default: host=localhost, user=root, pass=(empty)
 */
$DB_HOST = 'localhost';
$DB_NAME = 'choir_db';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('<h2 style="color:red;font-family:sans-serif;">Database connection failed: '
        . htmlspecialchars($e->getMessage()) . '</h2>');
}
