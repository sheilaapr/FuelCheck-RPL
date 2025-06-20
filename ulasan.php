<?php
session_start();
include_once 'auth/db.php';

// Cek role admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan SPBU User - FuelCheck</title>
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
    <div class="w-full max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-blue-800 mb-6 text-center">Ulasan SPBU dari User</h1>
        <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">SPBU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Ulasan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
            <?php
            if ($conn->query("SHOW TABLES LIKE 'ulasan_spbu'")->num_rows > 0) {
                $result = $conn->query("SELECT u.*, s.nama_spbu, s.lokasi, us.nama as nama_user FROM ulasan_spbu u JOIN spbu s ON u.spbu_id = s.id JOIN users us ON u.user_id = us.id ORDER BY u.created_at DESC");
            } elseif ($conn->query("SHOW TABLES LIKE 'ulasan'")->num_rows > 0) {
                $result = $conn->query("SELECT u.*, u.spbu as nama_spbu, u.rating, u.komentar as ulasan, u.created_at, us.nama as nama_user FROM ulasan u JOIN users us ON u.id_user = us.id ORDER BY u.created_at DESC");
            } else {
                $result = false;
            }
            if ($result && $result->num_rows > 0):
                while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?= htmlspecialchars($row['nama_user']) ?></td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($row['nama_spbu']) ?></div>
                        <?php if(isset($row['lokasi'])): ?><div class="text-xs text-gray-500"><?= htmlspecialchars($row['lokasi']) ?></div><?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-1">
                        <?php $rating = isset($row['rating']) ? $row['rating'] : 0; for($i=1; $i<=5; $i++): ?>
                            <?php if($i <= $rating): ?><i class="ri-star-fill text-yellow-400"></i><?php else: ?><i class="ri-star-line text-gray-300"></i><?php endif; ?>
                        <?php endfor; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        <?php 
                            $isi_ulasan = '';
                            if (isset($row['ulasan'])) {
                                $isi_ulasan = $row['ulasan'];
                            } elseif (isset($row['komentar'])) {
                                $isi_ulasan = $row['komentar'];
                            }
                        ?>
                        "<?= htmlspecialchars($isi_ulasan) ?>"
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                </tr>
                <?php endwhile;
            else: ?>
                <tr><td colspan="5" class="text-center text-gray-500 py-6">Belum ada ulasan dari user.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</main>
</body>
</html>
