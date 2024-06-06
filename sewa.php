<?php
include 'config.php';

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: loginoperator.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ruangan = $_GET['id'];
    $nama_ruangan = isset($_GET['nama_ruangan']) ? $_GET['nama_ruangan'] : '';
    $nama_penyewa = $_POST['nama_penyewa'];
    $tlp_penyewa = $_POST['tlp_penyewa'];
    $mulai_sewa = $_POST['mulai_sewa'];
    $akhir_sewa = $_POST['akhir_sewa'];

    // Mendapatkan alat yang dipilih dari form
    $alat_pilihan = isset($_POST['alat_pilihan']) ? $_POST['alat_pilihan'] : [];

    // Memperbarui status ruangan menjadi 'Tidak Tersedia'
    $query_update_ruangan = "UPDATE ruangan SET status='Tidak Tersedia' WHERE id_ruangan=$id_ruangan";

    if (mysqli_query($conn, $query_update_ruangan)) {
        // Mengurangi quantity alat yang dipilih dan memperbarui status alat
        foreach ($alat_pilihan as $id_alat) {
            $query_update_alat = "UPDATE alat SET quantity = quantity - 1 WHERE id_alat = $id_alat";
            mysqli_query($conn, $query_update_alat);
        }

        // Menghitung total harga alat yang dipilih dan menyimpan nama alat yang dipilih
        $total_harga_alat = 0;
        $nama_alat_pilihan = [];
        foreach ($alat_pilihan as $id_alat) {
            $query_alat = "SELECT nama_alat, harga_alat FROM alat WHERE id_alat = $id_alat";
            $result_alat = mysqli_query($conn, $query_alat);
            $row_alat = mysqli_fetch_assoc($result_alat);
            $total_harga_alat += $row_alat['harga_alat'];
            $nama_alat_pilihan[] = $row_alat['nama_alat'];
        }

        // Menghitung durasi sewa
        $mulai_sewa_timestamp = strtotime($mulai_sewa);
        $akhir_sewa_timestamp = strtotime($akhir_sewa);
        $durasi_sewa_seconds = $akhir_sewa_timestamp - $mulai_sewa_timestamp;
        $durasi_sewa_hours = $durasi_sewa_seconds / 3600; // Menghitung durasi dalam jam
        $durasi_sewa = gmdate("H:i:s", $durasi_sewa_seconds);

        // Mengambil harga ruangan dari tabel ruangan
        $query_harga_ruangan = "SELECT harga_ruangan FROM ruangan WHERE id_ruangan=$id_ruangan";
        $result_harga_ruangan = mysqli_query($conn, $query_harga_ruangan);
        $row_harga_ruangan = mysqli_fetch_assoc($result_harga_ruangan);
        $harga_ruangan_per_jam = $row_harga_ruangan['harga_ruangan'];

        // Menghitung harga total
        $harga_total_ruangan = $durasi_sewa_hours * $harga_ruangan_per_jam;
        $harga_total = $harga_total_ruangan + $total_harga_alat;

        // Memasukkan data transaksi ke tabel transaksi
        $query_insert_transaksi = "INSERT INTO transaksi (n_ruangan, penyewa, awal_sewa, akhir_sewa, tlp_penyewa, harga_total, alat, durasi_sewa) VALUES ('$nama_ruangan', '$nama_penyewa', '$mulai_sewa', '$akhir_sewa', '$tlp_penyewa', $harga_total, '" . implode(", ", $nama_alat_pilihan) . "', '$durasi_sewa')";
        mysqli_query($conn, $query_insert_transaksi);

        header("Location: dashboardoperator.php");
        exit();
    } else {
        echo "Error: " . $query_update_ruangan . "<br>" . mysqli_error($conn);
    }
}
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
        <h2>Sewa Ruangan: <?php echo htmlspecialchars(isset($_GET['nama_ruangan']) ? $_GET['nama_ruangan'] : ''); ?></h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="nama_penyewa">Nama Penyewa:</label>
                <input type="text" id="nama_penyewa" name="nama_penyewa" required>
            </div>
            <div class="form-group">
                <label for="tlp_penyewa">Telepon Penyewa:</label>
                <input type="text" id="tlp_penyewa" name="tlp_penyewa" required>
            </div>
            <div class="form-group">
                <label for="mulai_sewa">Mulai Sewa:</label>
                <input type="datetime-local" id="mulai_sewa" name="mulai_sewa" required>
            </div>
            <div class="form-group">
                <label for="akhir_sewa">Akhir Sewa:</label>
                <input type="datetime-local" id="akhir_sewa" name="akhir_sewa" required>
            </div>

            <!-- Form for selecting equipment -->
            <h3>Pilih Alat</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Alat</th>
                        <th>Harga Alat</th>
                        <th>Jumlah Tersedia</th>
                        <th>Pilih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetching equipment data from the 'alat' table
                    $query_alat = "SELECT id_alat, nama_alat, quantity, harga_alat FROM alat";
                    $result_alat = mysqli_query($conn, $query_alat);
                    while ($row = mysqli_fetch_assoc($result_alat)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama_alat']); ?></td>
                            <td><?php echo htmlspecialchars($row['harga_alat']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>
                                <input type="checkbox" name="alat_pilihan[]" value="<?php echo htmlspecialchars($row['id_alat']); ?>">
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <button type="submit">Submit</button>
        </form>
        <a href="javascript:history.back()" class="back-button">Kembali</a>
    </div>
</body>
</html>
