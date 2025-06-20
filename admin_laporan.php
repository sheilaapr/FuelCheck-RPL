<?php 
include 'auth/db.php'; 

// Proses perubahan status laporan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $aksi = $_POST['aksi'];
    $status = ($aksi === 'valid') ? 'verifikasi' : 'ditolak';
    
    $stmt = $conn->prepare("UPDATE laporan SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    
    header("Location: admin_laporan.php");
    exit;
}

// Ambil laporan dengan status pending
$laporan = $conn->query("
    SELECT l.*, s.nama_spbu 
    FROM laporan l 
    JOIN spbu s ON l.spbu_id = s.id 
    WHERE l.status = 'pending' 
    ORDER BY l.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Verifikasi Laporan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <h1 class="text-3xl font-bold text-blue-700 mb-8">Verifikasi Laporan SPBU</h1>
    
    <?php if ($laporan->num_rows === 0): ?>
        <p class="text-gray-500 italic">Tidak ada laporan yang perlu diverifikasi.</p>
    <?php else: ?>
        <div class="overflow-x-auto bg-white shadow rounded-xl border">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-blue-100 text-blue-900 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3">SPBU</th>
                        <th class="px-6 py-3">Deskripsi</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while ($l = $laporan->fetch_assoc()): ?>
                        <tr class="hover:bg-blue-50">
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                <?= htmlspecialchars($l['nama_spbu']) ?>
                                <div class="text-xs text-gray-500"><?= date('d M Y, H:i', strtotime($l['created_at'])) ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700"><?= nl2br(htmlspecialchars($l['deskripsi'])) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= date('d M Y H:i', strtotime($l['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <form method="POST" class="flex gap-2">
                                    <input type="hidden" name="id" value="<?= $l['id'] ?>">
                                    <button name="aksi" value="valid" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">
                                        Valid
                                    </button>
                                    <button name="aksi" value="tolak" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                        Tolak
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>