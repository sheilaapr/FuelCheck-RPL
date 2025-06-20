<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssss", $nama, $email, $password, $role);

        if ($stmt->execute()) {
            if ($role === 'user') {
                header("Location: ../login.php?redirect=dasboardUser.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit;
        } else {
            echo "Registrasi gagal: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Gagal menyiapkan query: " . $conn->error;
    }
} else {
    echo "Akses tidak valid.";
}

