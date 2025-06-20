<?php
include 'auth/db.php';
$id = $_GET['id'] ?? 0;

$spbu = $conn->query("SELECT * FROM spbu WHERE id = $id")->fetch_assoc();
$ratings = $conn->query("SELECT r.*, u.nama AS user_nama FROM rating r JOIN users u ON r.user_id = u.id WHERE r.spbu_id = $id ORDER BY r.created_at DESC");
$laporan = $conn->query("SELECT l.*, u.nama AS pelapor_nama FROM laporan l JOIN users u ON l.user_id = u.id WHERE l.spbu_id = $id ORDER BY l.created_at DESC");
?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail SPBU - FuelCheck</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#3B82F6',
            secondary: '#10B981'
          },
          borderRadius: {
            'button': '8px'
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-green-100 min-h-screen">
  <header class="bg-white shadow sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
      <a href="dashboard.php" class="text-2xl font-['Pacifico'] text-primary">FuelCheck</a>
      <nav class="hidden md:flex space-x-6">
        <a href="dashboard.php" class="text-gray-600 hover:text-primary">Beranda</a>
        <a href="laporan.php" class="text-gray-600 hover:text-primary">Lapor</a>
        <a href="spbu.php" class="text-primary font-semibold">SPBU</a>
        <a href="#" class="text-gray-600 hover:text-primary">Profil</a>
      </nav>
    </div>
  </header>

  <main class="max-w-6xl mx-auto py-10 px-4">
    <div class="flex justify-between items-center mb-6">
        <a href="spbu.php" class="bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200 transition text-sm">
            &larr; Kembali ke Daftar SPBU</a>

        <a href="lapor.php?spbu_id=<?= $spbu['id'] ?>" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded hover:opacity-90 transition">Laporkan SPBU Ini</a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow mb-10">
      <h1 class="text-3xl font-bold text-blue-800 mb-2"><?= htmlspecialchars($spbu['nama_spbu']) ?></h1>
      <p class="text-gray-600 text-sm">Lokasi: <?= htmlspecialchars($spbu['lokasi']) ?></p>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
      <!-- Komentar Rating -->
      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold text-yellow-600 mb-4">Ulasan Pengguna</h2>
        <?php while ($r = $ratings->fetch_assoc()): ?>
            <div class="mb-5 border-b pb-3">
                <div class="font-semibold text-gray-800"><?= htmlspecialchars($r['user_nama']) ?></div>
                    <div class="text-yellow-500 flex">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?= $i <= $r['nilai'] ? '<i class="ri-star-fill"></i>' : '<i class="ri-star-line"></i>' ?>
                        <?php endfor; ?>
                    </div>
                    <p class="text-sm text-gray-700 mt-1">"<?= htmlspecialchars($r['komentar']) ?>"</p>
                    <p class="text-xs text-gray-400 mt-1"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></p>
            </div>
        <?php endwhile; ?>
      </div>

      <!-- Laporan -->
      <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold text-red-600 mb-4">Laporan Kecurangan</h2>
        <?php while ($l = $laporan->fetch_assoc()): ?>
        <div class="mb-5 border-b pb-4">
            <p class="text-sm font-semibold text-gray-800">Dilaporkan oleh: <?= htmlspecialchars($l['pelapor_nama']) ?></p>
            <p class="text-sm text-gray-700 mt-1">Deskripsi: <?= htmlspecialchars($l['deskripsi']) ?></p>
            <p class="text-xs text-gray-500 mt-1"><?= date('d M Y H:i', strtotime($l['created_at'])) ?></p>
            
            <div class="flex flex-wrap gap-3 mt-2">
                <?php
                $bukti = $conn->query("SELECT * FROM bukti_laporan WHERE laporan_id = {$l['id']}");
                while ($b = $bukti->fetch_assoc()):
                ?>
                <img src="uploads/<?= htmlspecialchars($b['file_path']) ?>" alt="Bukti" class="w-32 rounded shadow">
                <?php endwhile; ?>
            </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </main>

     <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="#" class="font-['Pacifico'] text-2xl text-white mb-4 block">FuelCheck</a>
                    <p class="text-gray-400 mb-4">Aplikasi untuk memantau dan melaporkan kecurangan BBM di SPBU.
                        Dikembangkan dengan metode Agile (Scrum) agar cepat beradaptasi dengan kebutuhan pengguna.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="ri-facebook-fill"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="ri-twitter-fill"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="ri-instagram-fill"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition">
                            <i class="ri-youtube-fill"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Link Penting</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Cara Kerja</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Bantuan</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Karir</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Kebijakan</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Kebijakan Cookie</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Kebijakan Refund</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Lisensi</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <i class="ri-map-pin-line mt-1 mr-2"></i>
                            <span class="text-gray-400">Jl. Jendral Sudirman No. 28, Jakarta Pusat, 10210</span>
                        </li>
                        <li class="flex items-center">
                            <i class="ri-phone-line mr-2"></i>
                            <span class="text-gray-400">+62 21 5678 9012</span>
                        </li>
                        <li class="flex items-center">
                            <i class="ri-mail-line mr-2"></i>
                            <span class="text-gray-400">info@fuelcheck.id</span>
                        </li>
                    </ul>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium mb-2">Berlangganan Newsletter</h4>
                        <div class="flex">
                            <input type="email" placeholder="Email Anda"
                                class="px-4 py-2 rounded-l-button text-gray-900 w-full border-none">
                            <button
                                class="bg-primary text-white px-4 py-2 rounded-r-button hover:bg-blue-600 transition whitespace-nowrap">Langganan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">© 2025 FuelCheck. Hak Cipta Dilindungi.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <i class="ri-visa-line text-gray-400 text-xl"></i>
                    <i class="ri-mastercard-line text-gray-400 text-xl"></i>
                    <i class="ri-paypal-line text-gray-400 text-xl"></i>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
