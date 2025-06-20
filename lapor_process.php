<?php
include 'auth/db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Validasi input
$user_id = $_SESSION['user']['id'];
$spbu_id = $_POST['spbu_id'] ?? '';
$jenis_bbm = $_POST['jenis_bbm'] ?? '';
$deskripsi = $_POST['deskripsi'] ?? '';
$status = 'pending';
$created_at = date('Y-m-d H:i:s');

// Upload file
$upload_dir = 'uploads/';
$bukti_path = '';
if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
    $filename = uniqid('bukti_') . '.' . $ext;
    $bukti_path = $upload_dir . $filename;
    move_uploaded_file($_FILES['bukti']['tmp_name'], $bukti_path);
}

// Simpan laporan ke tabel laporan
$stmt = $conn->prepare("INSERT INTO laporan (user_id, spbu_id, deskripsi, status, created_at) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param('iisss', $user_id, $spbu_id, $deskripsi, $status, $created_at);
$stmt->execute();
$laporan_id = $stmt->insert_id;
$stmt->close();

// Simpan bukti ke tabel bukti_laporan jika ada file
if ($bukti_path && $laporan_id) {
    $stmt2 = $conn->prepare("INSERT INTO bukti_laporan (laporan_id, file_path, file_type) VALUES (?, ?, 'image')");
    $stmt2->bind_param('is', $laporan_id, $bukti_path);
    $stmt2->execute();
    $stmt2->close();
}

// Tampilkan halaman sukses dengan HTML langsung dan CSS Tailwind
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .success-anim {
            animation: pop 0.6s cubic-bezier(.36,1.64,.56,1) 1;
        }
        @keyframes pop {
            0% { transform: scale(0.7); opacity: 0; }
            80% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-100 via-white to-blue-100 min-h-screen flex flex-col items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md text-center mt-12 success-anim">
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="#d1fae5"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4" stroke="#10b981" stroke-width="2"/></svg>
        </div>
        <h2 class="text-2xl font-bold text-green-600 mb-2">Laporan Berhasil Dikirim!</h2>
        <p class="mb-6 text-gray-700">Terima kasih telah melaporkan kecurangan SPBU.<br>Laporan Anda akan segera diverifikasi oleh admin.</p>
        <a href="dashboard.php" class="inline-block bg-primary text-white px-6 py-2 rounded hover:bg-blue-700 transition">Kembali ke Dashboard</a>
    </div>
</body>
</html>
<?php
