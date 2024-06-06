<?php
include 'config.php'; // Menyertakan file konfigurasi database

// Memeriksa apakah koneksi berhasil dibuat
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Memeriksa apakah ada permintaan logout
if (isset($_POST['logout'])) {
    // Menghapus session dan mengarahkan pengguna ke halaman login operator
    session_start();
    session_unset();
    session_destroy();
    header("Location: loginoperator.php");
    exit();
}

// Query untuk mengambil data dari tabel transaksi
$query = "SELECT * FROM transaksi";
$result = mysqli_query($conn, $query);

// Menginisialisasi array untuk menyimpan data transaksi
$transactions = array();

// Memeriksa apakah ada hasil dari query
if (mysqli_num_rows($result) > 0) {
    // Memasukkan data ke dalam array transactions
    while ($row = mysqli_fetch_assoc($result)) {
        $transactions[] = $row;
    }
}

// Menutup koneksi database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboardoperator.css"> <!-- Sesuaikan dengan file CSS Anda -->
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>

        <!-- Tombol Kembali ke Dashboard Manager -->
        <a href="dashboardmanager.php" class="back-button">Kembali ke Dashboard Manager</a>

        <!-- Tabel data transaksi -->
        <table>
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Penyewa</th>
                    <th>Tanggal Transaksi</th>
                    <th>Awal Sewa</th>
                    <th>Akhir Sewa</th>
                    <th>Harga Total</th>
                    <th>Alat</th>
                    <th>No. Telp. Penyewa</th>
                    <th>Durasi Sewa</th>
                    <th>Nama Ruangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo $transaction['id_transaksi']; ?></td>
                        <td><?php echo $transaction['penyewa']; ?></td>
                        <td><?php echo $transaction['tanggal_transaksi']; ?></td>
                        <td><?php echo $transaction['awal_sewa']; ?></td>
                        <td><?php echo $transaction['akhir_sewa']; ?></td>
                        <td><?php echo $transaction['harga_total']; ?></td>
                        <td><?php echo $transaction['alat']; ?></td>
                        <td><?php echo $transaction['tlp_penyewa']; ?></td>
                        <td><?php echo $transaction['durasi_sewa']; ?></td>
                        <td><?php echo $transaction['n_ruangan']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
