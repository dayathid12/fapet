<?php

$host = '127.0.0.1';
$port = '3306';
$user = 'sarpras_u1';
$pass = '123456';
$dbname = 'sarpras_db';

try {
    // Connect without specifying database
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Drop the database if it exists
    $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");
    echo "Database '$dbname' dropped successfully.\n";

    // Create the database
    $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbname' created successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
