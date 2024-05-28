<?php
include 'config.php';

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_ruangan = $_GET['nama_ruangan']; // Ubah ini sesuai dengan cara Anda mendapatkan nama ruangan
    $penyewa = $_POST['penyewa'];
    $tlp_penyewa = $_POST['tlp_penyewa'];
    $awal_sewa = $_POST['awal_sewa'];
    $akhir_sewa = $_POST['akhir_sewa'];
    $alat_pilihan = $_POST['alat_pilihan']; // This will be an array of selected alat IDs

    // Fetching the names of the selected equipment
    $alat_names = [];
    foreach ($alat_pilihan as $id_alat) {
        $query_alat_name = "SELECT nama_alat FROM alat WHERE id_alat = $id_alat";
        $result_alat_name = mysqli_query($conn, $query_alat_name);
        $row_alat_name = mysqli_fetch_assoc($result_alat_name);
        $alat_names[] = $row_alat_name['nama_alat'];
    }
    $alat_names_str = implode(", ", $alat_names);

    // Calculate the duration of rental
    $start_time = new DateTime($awal_sewa);
    $end_time = new DateTime($akhir_sewa);
    $interval = $start_time->diff($end_time);
    $duration_seconds = $interval->s + $interval->i * 60 + $interval->h * 3600;
    $duration_time = gmdate('H:i:s', $duration_seconds); // Convert duration to HH:MM:SS format

    // Fetching harga_ruangan
    $query_harga = "SELECT harga_ruangan FROM ruangan WHERE nama_ruangan = '$nama_ruangan'";
    $result_harga = mysqli_query($conn, $query_harga);
    $row_harga = mysqli_fetch_assoc($result_harga);
    $harga_ruangan = $row_harga['harga_ruangan'];

    // Calculate the total cost
    $total_cost = $harga_ruangan * ($duration_seconds / 3600); // Convert duration to hours

    // Reduce the quantity of selected equipment
    foreach ($alat_pilihan as $id_alat) {
        $query_update_alat = "UPDATE alat SET quantity = quantity - 1 WHERE id_alat = $id_alat";
        mysqli_query($conn, $query_update_alat);
    }

    // Insert transaction into transaksi table
    $query_insert_transaksi = "INSERT INTO transaksi (ruangan, penyewa, awal_sewa, akhir_sewa, tlp_penyewa, harga_total, durasi_sewa, alat) VALUES ('$nama_ruangan', '$penyewa', '$awal_sewa', '$akhir_sewa', '$tlp_penyewa', '$total_cost', '$duration_time', '$alat_names_str')";

    if (mysqli_query($conn, $query_insert_transaksi)) {
        header("Location: dashboardoperator.php");
        exit();
    } else {
        echo "Error: " . $query_insert_transaksi . "<br>" . mysqli_error($conn);
    }
}

// Fetching equipment data from the 'alat' table
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
    <script>
        function updateAlatPilihan() {
            var checkboxes = document.querySelectorAll('input[name="alat_pilihan[]"]:checked');
            var selectedAlat = [];
            checkboxes.forEach((checkbox) => {
                selectedAlat.push(checkbox.value);
            });
            document.getElementById('selectedAlat').value = JSON.stringify(selectedAlat);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Sewa Ruangan</h2>
        <form method="post" action="" onsubmit="updateAlatPilihan()">
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
            <input type="hidden" id="selectedAlat" name="selectedAlat">
            <div class="form-group">
                <label for="penyewa">Nama Penyewa:</label>
                <input type="text" id="penyewa" name="penyewa" required>
            </div>
            <div class="form-group">
                <label for="tlp_penyewa">Telepon Penyewa:</label>
                <input type="text" id="tlp_penyewa" name="tlp_penyewa" required>
            </div>
            <div class="form-group">
                <label for="awal_sewa">Awal Sewa:</label>
                <input type="datetime-local" id="awal_sewa" name="awal_sewa" required>
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
