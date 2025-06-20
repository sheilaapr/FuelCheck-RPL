<?php
// rating_process.php
include 'auth/db.php';
session_start();

$spbu_id = $_POST['spbu_id'];
$user_id = $_SESSION['user_id'] ?? 1; // default user if session not set
$nilai = $_POST['nilai'];
$komentar = $_POST['komentar'];

$stmt = $conn->prepare("INSERT INTO rating (spbu_id, user_id, nilai, komentar) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $spbu_id, $user_id, $nilai, $komentar);
$stmt->execute();

header("Location: spbu_detail.php?id=" . $spbu_id);
exit;
