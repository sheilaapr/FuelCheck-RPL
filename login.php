<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - FuelCheck</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .backdrop-blur-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body
    class="min-h-screen bg-gradient-to-br from-blue-300 via-blue-100 to-green-200 flex items-center justify-center relative">

    <!-- Tombol Kembali -->
    <a href="index.php"
        class="absolute top-6 left-6 flex items-center text-gray-700 hover:text-blue-700 transition font-medium">
        <i class="ri-arrow-left-line text-2xl mr-2"></i> Kembali
    </a>

    <div class="p-8 rounded-2xl shadow-2xl w-full max-w-md backdrop-blur-card border border-blue-100">
        <div class="flex justify-center mb-4">
            <i class="ri-gas-station-fill text-5xl text-primary"></i>
        </div>
        <h2 class="text-3xl font-bold text-center text-primary mb-1">FuelCheck</h2>
        <p class="text-center text-gray-700 mb-6">Masuk ke akun Anda untuk melanjutkan</p>

        <form action="auth/login_process.php" method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-md">
                <i class="ri-login-box-line mr-1"></i> Masuk
            </button>
        </form>

        <p class="text-sm text-center text-gray-700 mt-4">Belum punya akun?
            <a href="register.php" class="text-blue-700 font-medium hover:underline">Daftar sekarang</a>
        </p>
    </div>

</body>

</html>