<?php
include 'auth/db.php';

// Proses aksi verifikasi/tolak jika ada parameter
if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id = intval($_GET['id']);
    $aksi = $_GET['aksi'];
    if ($aksi === 'verifikasi') {
        $status = 'verifikasi';
    } elseif ($aksi === 'tolak') {
        $status = 'selesai'; // enum yang diizinkan di database
    } else {
        header('Location: verifikasi_laporan.php');
        exit();
    }
    $conn->query("UPDATE laporan SET status='$status' WHERE id=$id");
    header('Location: verifikasi_laporan.php#detail-' . $id);
    exit();
}

// Ambil semua laporan
$laporan = [];
$query = "
    SELECT laporan.id, laporan.deskripsi, laporan.status, laporan.created_at, spbu.nama_spbu, spbu.lokasi
    FROM laporan
    JOIN spbu ON laporan.spbu_id = spbu.id
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
<title>Verifikasi Laporan - FuelCheck</title>
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
    <nav class="hidden md:flex items-center space-x-6">
        <a href="dashboard.php" class="text-gray-600 font-medium hover:text-primary transition">Beranda</a>
        <a href="verifikasi_laporan.php" class="text-gray-900 hover:text-primary transition">Verifikasi Laporan</a>
        <a href="spbu.php" class="text-gray-600 hover:text-primary transition">SPBU</a>
        <a href="#" class="text-gray-600 hover:text-primary transition">Profil</a>
    </nav>
    </div>
</header>
<main class="flex-grow w-full px-4 py-10">
    <div class="w-full max-w-7xl mx-auto">
    <h1 class="text-4xl font-extrabold text-blue-800 mb-8">Verifikasi Laporan User</h1>
    <?php
    // Notifikasi status terakhir
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $cek = $conn->query("SELECT status FROM laporan WHERE id=$id LIMIT 1");
        if ($cek && $cek->num_rows > 0) {
            $rowNotif = $cek->fetch_assoc();
            $notifStatus = strtolower(trim($rowNotif['status']));
            if ($notifStatus === 'verifikasi') {
                echo '<div class="mb-4 p-3 rounded bg-green-100 text-green-800 font-semibold text-center">Laporan berhasil diverifikasi!</div>';
            } elseif ($notifStatus === 'selesai') {
                echo '<div class="mb-4 p-3 rounded bg-red-100 text-red-800 font-semibold text-center">Laporan tidak diverifikasi (Non-Verifikasi).</div>';
            } else {
                echo '<div class="mb-4 p-3 rounded bg-gray-100 text-gray-800 font-semibold text-center">Status laporan: ' . htmlspecialchars(ucfirst($notifStatus)) . '</div>';
            }
        }
    }
    ?>
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
            <?php foreach ($laporan as $lapor): ?>
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
                } elseif ($status === 'tolak') {
                    $badge = 'bg-red-100 text-red-700';
                    $label = 'Non-Verifikasi';
                } else {
                    $badge = 'bg-red-100 text-red-700';
                    $label = !empty($status) ? ucfirst($status) : 'non-verifikasi';
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
                    <a href="verifikasi_laporan.php?id=<?= $lapor['id'] ?>&aksi=verifikasi" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 transition">Terima</a>
                    <a href="verifikasi_laporan.php?id=<?= $lapor['id'] ?>&aksi=tolak" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700 transition">Tolak</a>
                </div>
                <?php endif; ?>
            </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    </div>
</main>
<script>
    function toggleDetail(id) {
    const row = document.getElementById('detail-' + id);
    row.classList.toggle('hidden');
    }
</script>
</body>
</html>
