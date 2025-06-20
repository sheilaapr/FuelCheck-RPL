<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = $conn->prepare("SELECT * FROM users WHERE email = ?");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        // Redirect sesuai role
        if ($user['role'] === 'admin') {
            header("Location: ../dashboard.php");
        } else {
            header("Location: ../dashboardUser.php");
        }
        exit();
    }
}
echo "Login gagal. <a href='../login.php'>Coba lagi</a>";
