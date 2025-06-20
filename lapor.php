<?php
include "auth/db.php";
session_start();

// Jika belum login, redirect
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Ambil daftar SPBU untuk dropdown
$spbuResult = $conn->query("SELECT id, nama_spbu, lokasi FROM spbu");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lapor Kecurangan - FuelCheck</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-100 via-white to-blue-100 py-10 min-h-screen flex justify-center items-start relative">

    <!-- Tombol Kembali -->
    <a href="index.php" class="absolute top-6 left-6 flex items-center text-gray-700 hover:text-blue-700 transition font-medium">
        <i class="ri-arrow-left-line text-2xl mr-2"></i>  ← Kembali
    </a>

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-xl mt-12">
        <h2 class="text-2xl font-bold text-primary mb-6 text-center">Form Laporan Kecurangan SPBU</h2>

        <form action="lapor_process.php" method="post" enctype="multipart/form-data" class="space-y-4">

            <!-- Dropdown SPBU -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">SPBU</label>
                <select name="spbu_id" required class="w-full border px-4 py-2 rounded-md">
                    <option value="">Pilih SPBU</option>

                    <!-- Data Dummy -->
                    <option value="001">SPBU 001 - Jl. Merdeka No. 10</option>
                    <option value="002">SPBU 002 - Jl. Soekarno Hatta No. 5</option>
                    <option value="003">SPBU 003 - Jl. Gajah Mada No. 21</option>
                    <option value="004">SPBU 004 - Jl. Diponegoro No. 8</option>
                    <option value="005">SPBU 005 - Jl. Pahlawan No. 30</option>
                    <option value="006">SPBU 006 - Jl. Sudirman No. 12</option>
                    <option value="007">SPBU 007 - Jl. Siliwangi No. 45</option>
                    <option value="008">SPBU 008 - Jl. Veteran No. 3</option>
                    <option value="009">SPBU 009 - Jl. Kalimalang No. 17</option>
                    <option value="010">SPBU 010 - Jl. Ahmad Yani No. 9</option>
                    <option value="011">SPBU 011 - Jl. Cempaka Putih No. 22</option>
                    <option value="012">SPBU 012 - Jl. Kawi Atas No. 11</option>
                    <option value="013">SPBU 013 - Jl. Letjen Sutoyo No. 6</option>
                    <option value="014">SPBU 014 - Jl. Trunojoyo No. 20</option>
                    <option value="015">SPBU 015 - Jl. Basuki Rahmat No. 14</option>
                    <option value="016">SPBU 016 - Jl. Raya Tlogomas No. 55</option>
                    <option value="017">SPBU 017 - Jl. MT Haryono No. 19</option>
                    <option value="018">SPBU 018 - Jl. Raya Singosari No. 3</option>
                    <option value="019">SPBU 019 - Jl. Pandanaran No. 18</option>
                    <option value="020">SPBU 020 - Jl. Rajawali Selatan No. 2</option>

                    <!-- Data dari Database -->
                    <?php while ($spbu = $spbuResult->fetch_assoc()): ?>
                        <option value="<?= $spbu['id'] ?>">
                            <?= htmlspecialchars($spbu['nama_spbu']) ?> - <?= htmlspecialchars($spbu['lokasi']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Dropdown Jenis BBM -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Jenis BBM</label>
                <select name="jenis_bbm" required class="w-full border px-4 py-2 rounded-md">
                    <option value="">Pilih Jenis BBM</option>
                    <option value="pertalite">Pertalite (RON 90)</option>
                    <option value="pertamax">Pertamax (RON 92)</option>
                    <option value="pertamax_green_95">Pertamax Green 95 (RON 95)</option>
                    <option value="pertamax_turbo">Pertamax Turbo (RON 98)</option>
                    <option value="shell_super">Shell Super</option>
                    <option value="shell_vpower">Shell V-Power</option>
                    <option value="bp_90">BP 90</option>
                    <option value="bp_92">BP 92</option>
                    <option value="revvo_90">Revvo 90</option>
                    <option value="revvo_92">Revvo 92</option>
                    <option value="revvo_95">Revvo 95</option>
                    <option value="biosolar">Biosolar (B35)</option>
                    <option value="dexlite">Dexlite (CN 51)</option>
                    <option value="pertamina_dex">Pertamina Dex (CN 53)</option>
                    <option value="shell_diesel_extra">Shell Diesel Extra</option>
                    <option value="bp_diesel">BP Diesel</option>
                </select>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Deskripsi Laporan</label>
                <textarea name="deskripsi" required rows="4" class="w-full border px-4 py-2 rounded-md"></textarea>
            </div>

            <!-- Upload Bukti -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Bukti Foto</label>
                <input type="file" name="bukti" accept="image/*" required class="w-full border px-4 py-2 rounded-md">
            </div>

            <!-- Tombol Submit -->
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Kirim Laporan
                </button>
            </div>

        </form>
    </div>

</body>
</html>
