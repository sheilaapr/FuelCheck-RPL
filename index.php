<?php
session_start();
include 'auth/db.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - FuelCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#22c55e'
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        pacifico: ['Pacifico', 'cursive']
                    },
                    animation: {
                        fadeIn: 'fadeIn 1.5s ease-in-out forwards'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: 0, transform: 'translateY(20px)' },
                            '100%': { opacity: 1, transform: 'translateY(0)' }
                        }
                    }
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-section {
            background-image: url('index.jpeg'); /* Gambar SPBU modern dengan efek transparan */
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-overlay {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.85) 0%, rgba(34, 197, 94, 0.85) 100%);
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 10;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <a href="dashboard.php" class="text-2xl font-pacifico text-primary">FuelCheck</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section min-h-screen flex items-center justify-center">
        <div class="hero-overlay"></div>
        <div class="hero-content text-center px-6 animate-fadeIn">
            <h1 class="text-5xl font-bold text-white drop-shadow-lg mb-4">Selamat Datang di FuelCheck!</h1>
            <p class="text-xl text-white/90 mb-6">Pantau dan laporkan kecurangan BBM di SPBU terdekat.<br>Kita wujudkan distribusi yang adil, jujur, dan transparan.</p>
            <p class="text-white mb-6">Silakan masuk atau daftar untuk memulai:</p>
            <div class="flex justify-center space-x-4">
                <a href="login.php" class="bg-primary text-white px-6 py-3 rounded-full text-lg font-medium hover:bg-blue-700 shadow-md transition">Masuk</a>
                <a href="register.php" class="bg-secondary text-white px-6 py-3 rounded-full text-lg font-medium hover:bg-green-700 shadow-md transition">Daftar</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <hr class="border-gray-700 mb-4">
            <p class="text-gray-400 text-sm">© 2025 FuelCheck. Hak Cipta Dilindungi.</p>
        </div>
    </footer>
</body>

</html>