<?php
// Setup script for SQLite
require_once 'db_connect.php';

echo "Memulai inisialisasi database SQLite...\n";

try {
    // Database and table creation is already handled in db_connect.php
    // But we can add a default admin if needed
    $username = 'admin';
    $password = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM admins WHERE username = :username");
    $stmt->execute([':username' => $username]);

    if (!$stmt->fetch()) {
        $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
        $stmt->execute([':username' => $username, ':password' => $password]);
        echo "Akun admin default berhasil dibuat: admin / admin123\n";
    } else {
        echo "Akun admin sudah ada.\n";
    }

    echo "Inisialisasi selesai. Database tersimpan di ds_fish_hunter.sqlite\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>