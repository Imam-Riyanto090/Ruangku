<?php
session_start(); // Mulai sesi di awal kode

include 'config.php';

// Periksa koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Periksa apakah tombol logout ditekan
if(isset($_POST['logout'])) {
    session_unset(); // Hapus semua variabel sesi
    session_destroy(); // Hapus sesi
    header("Location: loginadmin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="dashboardadmin.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard Admin</h1>
        <?php 
        // Periksa apakah sesi username ada sebelum mencoba untuk mengaksesnya
        if(isset($_SESSION['username'])) {
            echo "<p>Selamat datang, " . htmlspecialchars($_SESSION['username']) . "!</p>";
        }
        ?>
        
        <div class="nav-buttons">
            <button onclick="showSection('userSection')">Data User</button>
            <button onclick="showSection('roomSection')">Data Ruang</button>
            <button onclick="showSection('toolSection')">Data Alat</button>
        </div>

        <div id="userSection" class="section">
            <h2>Data Pengguna</h2>
            <!-- Tempatkan konten untuk Data User di sini -->
        </div>

        <div id="roomSection" class="section" style="display:none;">
            <h2>Data Ruangan</h2>
            <!-- Tempatkan konten untuk Data Ruang di sini -->
        </div>

        <div id="toolSection" class="section" style="display:none;">
            <h2>Data Alat</h2>
            <!-- Tempatkan konten untuk Data Alat di sini -->
        </div>

        <form method="post" action="logout.php">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
    </div>

    <script>
        function showSection(section) {
            document.querySelectorAll('.section').forEach(function(el) {
                el.style.display = 'none';
            });
            document.getElementById(section).style.display = 'block';
        }
    </script>
</body>
</html>
