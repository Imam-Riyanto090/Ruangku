<?php
include 'config.php';

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$query = "SELECT * FROM ruangan";
$result = mysqli_query($conn, $query);

if(isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: loginoperator.php");
    exit();
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
        }
        
        // Memperbarui status sewa di tabel transaksi jika ada transaksi yang terkait
        $query_update_transaksi = "UPDATE transaksi SET status_sewa = 'Sudah Disewa' WHERE id_ruangan = $id_ruangan AND status_sewa = 'Belum Disewa'";
        mysqli_query($conn, $query_update_transaksi);

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
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboardoperator.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>

        <form method="GET" action="">
            <div class="search-box">
                <select name="search_category">
                    <option value="nama_ruangan">Nama Ruangan</option>
                    <option value="kapasitas">Kapasitas Ruangan</option>
                    <option value="id_ruangan">ID Ruangan</option>
                </select>
                <input type="text" name="search_query" placeholder="Search...">
                <button type="submit">Search</button>
            </div>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID Ruangan</th>
                        <th>Nama Ruangan</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th>Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search_category = isset($_GET['search_category']) ? $_GET['search_category'] : '';
                    $search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

                    $query = "SELECT * FROM ruangan";

                    if (!empty($search_category) && !empty($search_query)) {
                        $query .= " WHERE $search_category LIKE '%$search_query%'";
                    }

                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$row['id_ruangan']."</td>";
                        echo "<td>".$row['nama_ruangan']."</td>";
                        echo "<td>".$row['kapasitas']."</td>";
                        echo "<td>".$row['status']."</td>";

                        // Jika status ruangan adalah "Tidak Tersedia", maka tampilkan teks "Sudah Disewa" dan tidak aktifkan tombol
                        if ($row['status'] == "Tidak Tersedia") {
                            echo "<td>Sudah Disewa</td>";
                        } else {
                            echo "<td><a href='sewa.php?id=".$row['id_ruangan']."'>Sewa</a></td>";
                        }

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <form method="post" action="">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html>
