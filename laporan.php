<?php
include 'auth/db.php';

// Ambil filter
$where = [];
if (!empty($_GET['cari'])) {
    $cari = $conn->real_escape_string($_GET['cari']);
    $where[] = "(spbu.nama_spbu LIKE '%$cari%' OR spbu.lokasi LIKE '%$cari%')";
}
if (!empty($_GET['status'])) {
    $status = $conn->real_escape_string($_GET['status']);
    $where[] = "laporan.status = '$status'";
}
$filter = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Ambil laporan dengan join ke tabel spbu
$laporan = [];
$query = "
    SELECT laporan.id, laporan.deskripsi, laporan.status, laporan.created_at, spbu.nama_spbu, spbu.lokasi
    FROM laporan
    JOIN spbu ON laporan.spbu_id = spbu.id
    $filter
    ORDER BY laporan.created_at DESC
";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $laporan[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan - FuelCheck</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#3B82F6',
            secondary: '#10B981',
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
    body {
      font-family: 'Inter', sans-serif;
    }
    .badge {
      @apply px-3 py-1 text-xs rounded-full font-semibold;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-green-100 min-h-screen flex flex-col">
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <a href="dashboard.php" class="font-['Pacifico'] text-2xl text-primary">FuelCheck</a>
      <nav class="hidden md:flex items-center space-x-6">
        <a href="dashboard.php" class="text-gray-600 font-medium hover:text-primary transition">Beranda</a>
        <a href="laporan.php" class="text-gray-900 hover:text-primary transition">Lapor</a>
        <a href="spbu.php" class="text-gray-600 hover:text-primary transition">SPBU</a>
        <a href="#" class="text-gray-600 hover:text-primary transition">Profil</a>
      </nav>
    </div>
  </header>

  <main class="flex-grow w-full px-4 py-10">
    <div class="w-full max-w-7xl mx-auto">
      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-4xl font-extrabold text-blue-800">Laporan Terkini</h1>
          <p class="text-gray-600 text-sm mt-1">Laporan kecurangan BBM yang telah diverifikasi atau sedang diproses</p>
        </div>
        <a href="lapor.php" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">
          + Buat Laporan
        </a>
      </div>

      <form method="GET" class="flex flex-wrap gap-4 mb-6">
        <input type="text" name="cari" placeholder="Cari SPBU atau Lokasi" value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>" class="px-4 py-2 border rounded-lg w-full md:w-1/3">
        <select name="status" class="px-4 py-2 border rounded-lg w-full md:w-1/4">
          <option value="">Semua Status</option>
          <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="verifikasi" <?= ($_GET['status'] ?? '') === 'verifikasi' ? 'selected' : '' ?>>Terverifikasi</option>
          <option value="selesai" <?= ($_GET['status'] ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cari</button>
      </form>

      <div class="overflow-x-auto rounded-xl shadow-lg bg-white border mb-12">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-blue-100 text-blue-900 text-xs uppercase">
            <tr>
              <th class="px-6 py-3">SPBU</th>
              <th class="px-6 py-3">Jenis Kecurangan</th>
              <th class="px-6 py-3">Tanggal</th>
              <th class="px-6 py-3">Status</th>
              <th class="px-6 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <?php if (count($laporan) > 0): foreach ($laporan as $lapor): ?>
            <tr class="hover:bg-blue-50">
              <td class="px-6 py-4">
                <div class="font-bold text-gray-800"><?= htmlspecialchars($lapor['nama_spbu']) ?></div>
                <div class="text-gray-500 text-xs italic"><?= htmlspecialchars($lapor['lokasi']) ?></div>
              </td>
              <td class="px-6 py-4 text-gray-700 text-sm line-clamp-2">
                <?= nl2br(htmlspecialchars($lapor['deskripsi'])) ?>
              </td>
              <td class="px-6 py-4 text-gray-600">
                <?= date('d M Y', strtotime($lapor['created_at'])) ?>
              </td>
              <td class="px-6 py-4">
                <?php
                  $status = strtolower(trim($lapor['status']));
                  if ($status === 'pending') {
                    $badge = 'bg-yellow-100 text-yellow-700';
                    $label = 'Pending';
                  } elseif ($status === 'verifikasi') {
                    $badge = 'bg-green-100 text-green-700';
                    $label = 'Terverifikasi';
                  } elseif ($status === 'selesai') {
                    $badge = 'bg-red-100 text-red-700';
                    $label = 'Non-Verifikasi';
                  } else {
                    $badge = 'bg-gray-100 text-gray-700';
                    $label = ucfirst($status);
                  }
                ?>
                <span class="badge <?= $badge ?> shadow"> <?= $label ?> </span>
              </td>
              <td class="px-6 py-4">
                <button onclick="toggleDetail(<?= $lapor['id'] ?>)" class="text-indigo-600 font-semibold hover:underline">Detail</button>
              </td>
            </tr>
            <tr id="detail-<?= $lapor['id'] ?>" class="hidden bg-blue-50">
              <td colspan="5" class="px-6 py-4">
                <div class="text-sm text-gray-700 mb-2">
                  <strong>Deskripsi Lengkap:</strong> <?= nl2br(htmlspecialchars($lapor['deskripsi'])) ?>
                </div>
                <?php
                  $buktiQ = $conn->query("SELECT file_path FROM bukti_laporan WHERE laporan_id = " . intval($lapor['id']));
                  if ($buktiQ && $buktiQ->num_rows > 0):
                ?>
                  <div class="mt-2">
                    <strong>Bukti Laporan:</strong>
                    <div class="flex flex-wrap gap-3 mt-2">
                      <?php while($b = $buktiQ->fetch_assoc()): ?>
                        <a href="<?= htmlspecialchars($b['file_path']) ?>" target="_blank" class="inline-block">
                          <img src="<?= htmlspecialchars($b['file_path']) ?>" alt="Bukti" class="w-24 h-24 object-cover rounded shadow border hover:scale-105 transition" />
                        </a>
                      <?php endwhile; ?>
                    </div>
                  </div>
                <?php else: ?>
                  <div class="text-xs text-gray-400 italic mt-2">Tidak ada bukti laporan.</div>
                <?php endif; ?>
                <?php if (strtolower($lapor['status']) === 'pending'): ?>
                  <div class="mt-4 flex gap-3">
                    <a href="verifikasi_laporan.php?id=<?= $lapor['id'] ?>&aksi=verifikasi" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 transition">Verifikasi</a>
                    <a href="verifikasi_laporan.php?id=<?= $lapor['id'] ?>&aksi=tolak" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700 transition">Tolak</a>
                  </div>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
              <td colspan="5" class="text-center py-8 text-gray-400">Belum ada laporan yang masuk.</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
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
    
  <script>
    function toggleDetail(id) {
      const row = document.getElementById('detail-' + id);
      row.classList.toggle('hidden');
    }
  </script>
</body>
</html>
