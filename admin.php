<?php
include 'auth/db.php'; // Ganti dengan file koneksi databasenya

// Proses verifikasi laporan
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = ($_GET['action'] == 'valid') ? 'valid' : 'ditolak';

    $update = mysqli_query($conn, "UPDATE laporan SET status='$status' WHERE id='$id'");
    if ($update) {
        echo "<script>alert('Status laporan berhasil diperbarui');window.location='admin_laporan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status');</script>";
    }
}

// Ambil laporan dengan status pending
$laporan = mysqli_query($conn, "SELECT * FROM laporan WHERE status='pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Laporan</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 5px 10px;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .btn-valid {
            background-color: green;
        }
        .btn-tolak {
            background-color: red;
        }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Verifikasi Laporan Pengguna</h2>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Pelapor</th>
            <th>Judul</th>
            <th>Isi</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($laporan)) {
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td><?= htmlspecialchars($row['isi']) ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td>
                <a href="?action=valid&id=<?= $row['id'] ?>" class="btn btn-valid">Valid</a>
                <a href="?action=tolak&id=<?= $row['id'] ?>" class="btn btn-tolak">Tolak</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
