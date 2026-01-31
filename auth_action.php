<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connect.php';

$action = $_POST['action'] ?? '';

if ($action === 'login') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Password salah!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Username tidak ditemukan!']);
    }
    $stmt->close();

} elseif ($action === 'register') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (strlen($username) < 3 || strlen($password) < 3) {
        echo json_encode(['success' => false, 'message' => 'Username/Password minimal 3 karakter']);
        exit;
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username sudah digunakan!']);
        exit;
    }
    $stmt->close();

    // Insert new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mendaftar: ' . $conn->error]);
    }
    $stmt->close();

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$conn->close();
?>