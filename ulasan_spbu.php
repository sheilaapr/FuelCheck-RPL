<?php
session_start();
include 'auth/db.php';

// Cek login user
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Ambil daftar SPBU
$spbu = $conn->query("SELECT id, nama_spbu, lokasi FROM spbu ORDER BY nama_spbu ASC");

// Proses submit ulasan
$notif = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $spbu_id = intval($_POST['spbu_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $ulasan = trim($_POST['ulasan'] ?? '');
    $user_id = $_SESSION['user']['id'];
    if ($spbu_id && $rating && $ulasan) {
        $stmt = $conn->prepare("INSERT INTO ulasan_spbu (spbu_id, user_id, rating, ulasan, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param('iiis', $spbu_id, $user_id, $rating, $ulasan);
        if ($stmt->execute()) {
            $notif = '<div class="mb-4 p-3 rounded bg-green-100 text-green-800 font-semibold text-center">Ulasan berhasil dikirim!</div>';
        } else {
            $notif = '<div class="mb-4 p-3 rounded bg-red-100 text-red-800 font-semibold text-center">Gagal mengirim ulasan.</div>';
        }
        $stmt->close();
    } else {
        $notif = '<div class="mb-4 p-3 rounded bg-yellow-100 text-yellow-800 font-semibold text-center">Semua field wajib diisi.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan SPBU - FuelCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-green-100 min-h-screen flex flex-col">
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <a href="dashboard.php" class="font-['Pacifico'] text-2xl text-primary">FuelCheck</a>
        <div class="relative group ml-4">
            <button id="profile-btn" class="flex items-center space-x-2 focus:outline-none">
                <i class="ri-user-3-line text-2xl text-primary"></i>
                <span class="hidden md:inline text-gray-700 font-medium">Profil</span>
            </button>
        </div>
    </div>
</header>
<main class="flex-grow w-full px-4 py-10">
    <div class="w-full max-w-xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-blue-800 mb-6 text-center">Tulis Ulasan SPBU</h1>
        <?= $notif ?>
        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Pilih SPBU</label>
                <select name="spbu_id" class="w-full border rounded px-4 py-2" required>
                    <option value="">-- Pilih SPBU --</option>
                    <?php if ($spbu && $spbu->num_rows > 0): while($s = $spbu->fetch_assoc()): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nama_spbu']) ?> - <?= htmlspecialchars($s['lokasi']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Rating</label>
                <select name="rating" class="w-full border rounded px-4 py-2" required>
                    <option value="">-- Pilih Rating --</option>
                    <option value="5">5 - Sangat Baik</option>
                    <option value="4">4 - Baik</option>
                    <option value="3">3 - Cukup</option>
                    <option value="2">2 - Kurang</option>
                    <option value="1">1 - Buruk</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Ulasan</label>
                <textarea name="ulasan" rows="4" class="w-full border rounded px-4 py-2" required placeholder="Tulis pengalaman Anda di SPBU ini..."></textarea>
            </div>
            <button type="submit" class="w-full bg-primary text-white py-2 rounded hover:bg-blue-700 transition">Kirim Ulasan</button>
        </form>
    </div>
</main>

<!-- Daftar Ulasan Saya -->
<section class="w-full max-w-xl mx-auto mt-8 mb-12">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-blue-800 mb-4 text-center">Ulasan Saya</h2>
        <?php
        $user_id = $_SESSION['user']['id'];
        // Cek jika tabel ulasan_spbu tidak ada, fallback ke tabel ulasan lama
        $ulasan_saya = false;
        if ($conn->query("SHOW TABLES LIKE 'ulasan_spbu'")->num_rows > 0) {
            $ulasan_saya = $conn->query("SELECT u.*, s.nama_spbu, s.lokasi FROM ulasan_spbu u JOIN spbu s ON u.spbu_id = s.id WHERE u.user_id = '$user_id' ORDER BY u.created_at DESC");
        } elseif ($conn->query("SHOW TABLES LIKE 'ulasan'")->num_rows > 0) {
            // fallback jika masih pakai tabel ulasan lama
            $ulasan_saya = $conn->query("SELECT * FROM ulasan WHERE id_user = '$user_id' ORDER BY created_at DESC");
        }
        if ($ulasan_saya && $ulasan_saya->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while($u = $ulasan_saya->fetch_assoc()): ?>
                    <div class="border rounded-lg p-4 bg-blue-50">
                        <div class="flex items-center justify-between mb-1">
                            <div class="font-semibold text-blue-900">
                                <?php if(isset($u['nama_spbu'])): ?>
                                    <?= htmlspecialchars($u['nama_spbu']) ?> <span class="text-xs text-gray-500">(<?= htmlspecialchars($u['lokasi']) ?>)</span>
                                <?php elseif(isset($u['spbu'])): ?>
                                    <?= htmlspecialchars($u['spbu']) ?>
                                <?php else: ?>
                                    SPBU
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center space-x-1">
                                <?php 
                                    $rating = 0;
                                    if (isset($u['rating'])) {
                                        $rating = $u['rating'];
                                    } elseif (isset($u['nilai'])) {
                                        $rating = $u['nilai'];
                                    }
                                ?>
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <?php if($i <= $rating): ?>
                                        <i class="ri-star-fill text-yellow-400"></i>
                                    <?php else: ?>
                                        <i class="ri-star-line text-gray-300"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php 
                            $isi_ulasan = '';
                            if (isset($u['ulasan'])) {
                                $isi_ulasan = $u['ulasan'];
                            } elseif (isset($u['komentar'])) {
                                $isi_ulasan = $u['komentar'];
                            }
                        ?>
                        <div class="text-gray-700 mb-1">"<?= htmlspecialchars($isi_ulasan) ?>"</div>
                        <div class="text-xs text-gray-500 text-right">
                            <?= isset($u['created_at']) ? date('d M Y H:i', strtotime($u['created_at'])) : '' ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-gray-500 text-center">Belum ada ulasan yang Anda kirimkan.</div>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
