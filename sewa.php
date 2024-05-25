<?php
include 'config.php';

session_start();

if (!isset($_SESSION['nama'])) {
    header("Location: loginoperator.php");
    exit();
}

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $penyewa = $_POST['penyewa'];
    $tlp_penyewa = $_POST['tlp_penyewa'];
    $mulai_sewa = $_POST['mulai_sewa'];
    $akhir_sewa = $_POST['akhir_sewa'];

    // Proses penyimpanan data sewa ke dalam tabel ruangan
    $insert_query = "INSERT INTO ruangan (penyewa, tlp_penyewa, mulai_sewa, akhir_sewa) VALUES ('$penyewa', '$tlp_penyewa', '$mulai_sewa', '$akhir_sewa')";

    mysqli_query($conn, $insert_query);

    // Setelah penyimpanan berhasil, redirect ke halaman dashboard atau halaman lain yang sesuai
    header("Location: dashboardoperator.php");
    exit();
}

// Ambil ID Ruangan dari parameter URL
$id_ruangan = $_GET['id'];

// Query untuk mendapatkan informasi ruangan berdasarkan ID Ruangan
$query_ruangan = "SELECT * FROM ruangan WHERE id_ruangan = $id_ruangan";
$result_ruangan = mysqli_query($conn, $query_ruangan);
$row_ruangan = mysqli_fetch_assoc($result_ruangan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sewa Ruangan</title>
    <link rel="stylesheet" href="sewa.css">
</head>
<body>
    <div class="container">
        <h1>Sewa Ruangan</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="id_ruangan" value="<?php echo $id_ruangan; ?>">
            <div class="form-group">
                <label for="penyewa">Nama Penyewa:</label>
                <input type="text" id="penyewa" name="penyewa" required>
            </div>
            <div class="form-group">
                <label for="tlp_penyewa">Nomor Telepon:</label>
                <input type="text" id="tlp_penyewa" name="tlp_penyewa" required>
            </div>
            <div class="form-group">
                <label for="mulai_sewa">Mulai Sewa:</label>
                <input type="datetime-local" id="mulai_sewa" name="mulai_sewa" required min="<?php echo date('Y-m-d\TH:i', strtotime('+2 hours')); ?>">
            </div>
            <div class="form-group">
                <label for="akhir_sewa">Akhir Sewa:</label>
                <input type="datetime-local" id="akhir_sewa" name="akhir_sewa" required min="<?php echo date('Y-m-d\TH:i', strtotime('+2 hours')); ?>">
            </div>
            <button type="submit">Sewa</button>
        </form>
    </div>
</body>
</html>
