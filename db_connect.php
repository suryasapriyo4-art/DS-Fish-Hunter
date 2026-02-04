<?php
// Database connection using SQLite (No XAMPP/MySQL required)
try {
    $db_file = __DIR__ . '/ds_fish_hunter.sqlite';
    $conn = new PDO("sqlite:$db_file");

    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Auto-create table if not exists
    $conn->exec("CREATE TABLE IF NOT EXISTS admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

} catch (PDOException $e) {
    // If error, display but in production should be logged
    die("Connection failed: " . $e->getMessage());
}
?>