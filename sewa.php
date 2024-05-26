<?php
include 'config.php';

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_ruangan = $_GET['id'];
    $nama_penyewa = $_POST['nama_penyewa'];
    $tlp_penyewa = $_POST['tlp_penyewa'];
    $mulai_sewa = $_POST['mulai_sewa'];
    $akhir_sewa = $_POST['akhir_sewa'];

    // Mendapatkan alat yang dipilih dari form
    $alat_pilihan = $_POST['alat_pilihan'];

    // Memperbarui status ruangan menjadi 'Tidak Tersedia'
    $query_update_ruangan = "UPDATE ruangan SET penyewa='$nama_penyewa', tlp_penyewa='$tlp_penyewa', mulai_sewa='$mulai_sewa', akhir_sewa='$akhir_sewa', status='Tidak Tersedia' WHERE id_ruangan=$id_ruangan";

    if (mysqli_query($conn, $query_update_ruangan)) {
        // Mengurangi quantity alat yang dipilih dan memperbarui status alat
        foreach ($alat_pilihan as $id_alat) {
            $query_update_alat = "UPDATE alat SET quantity = quantity - 1 WHERE id_alat = $id_alat";
            mysqli_query($conn, $query_update_alat);

            // Menyimpan nama alat yang dipilih ke dalam tabel 'ruangku'
            $query_insert_ruangku = "INSERT INTO ruangku (id_ruangan, id_alat) VALUES ($id_ruangan, $id_alat)";
            mysqli_query($conn, $query_insert_ruangku);
        }
        header("Location: dashboardoperator.php");
        exit();
    } else {
        echo "Error: " . $query_update_ruangan . "<br>" . mysqli_error($conn);
    }
}

// Mendapatkan data alat dari tabel 'alat'
$query_alat = "SELECT id_alat, nama_alat, quantity FROM alat";
$result_alat = mysqli_query($conn, $query_alat);
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
        <h2>Sewa Ruangan</h2>
        <!-- Menampilkan daftar alat yang tersedia dalam bentuk tabel -->
        <table>
            <thead>
                <tr>
                    <th>Nama Alat</th>
                    <th>Jumlah Tersedia</th>
                    <th>Pilih</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_alat)) { ?>
                    <tr>
                        <td><?php echo $row['nama_alat']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>
                            <input type="checkbox" name="alat_pilihan[]" value="<?php echo $row['id_alat']; ?>">
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
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
            <button type="submit">Submit</button>
        </form>
        <a href="javascript:history.back()" class="back-button">Kembali</a>
    </div>
</body>
</html>
