<?php
include 'config.php';

session_start();

if (!isset($_SESSION['nama'])) {
    header("Location: loginoperator.php");
    exit();
}

$nama = $_SESSION['nama']; 

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$query = "SELECT * FROM ruangan";
$result = mysqli_query($conn, $query);

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: loginoperator.php");
    exit();
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
        <p>Anda login sebagai :  <?php echo htmlspecialchars($nama); ?></p>
        
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
                        <th>Mulai Sewa</th>
                        <th>Akhir Sewa</th>
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
                        echo "<td>".htmlspecialchars($row['id_ruangan'])."</td>";
                        echo "<td>".htmlspecialchars($row['nama_ruangan'])."</td>";
                        echo "<td>".htmlspecialchars($row['kapasitas'])."</td>";
                        echo "<td>".htmlspecialchars($row['status'])."</td>";
                        echo "<td>".htmlspecialchars($row['mulai_sewa'])."</td>";
                        echo "<td>".htmlspecialchars($row['akhir_sewa'])."</td>";
                        echo "<td><a href='sewa.php?id=".htmlspecialchars($row['id_ruangan'])."'>Sewa</a></td>";
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
