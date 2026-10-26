<?php
$host = "localhost";
$dbname = "university_db";
$username = "root";
$password = ""; // Default XAMPP setup usually has no MySQL password.

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // Show a helpful message without exposing database credentials/details.
    exit("Database connection failed. Check that MySQL is running and university_db exists.");
}
