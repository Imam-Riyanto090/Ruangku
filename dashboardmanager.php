<?php
include 'config.php';

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if (isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: loginmanager.php");
    exit();
}

// Proses pembaruan harga ruangan
if(isset($_POST['update_ruangan'])) {
    $id_ruangan = $_POST['id_ruangan'];
    $harga_ruangan = $_POST['harga_ruangan'];

    $query_update_ruangan = "UPDATE ruangan SET harga_ruangan='$harga_ruangan' WHERE id_ruangan=$id_ruangan";

    if (mysqli_query($conn, $query_update_ruangan)) {
        $message = "Harga ruangan berhasil diperbarui";
    } else {
        $message = "Error updating record: " . mysqli_error($conn);
    }
}

// Proses pembaruan harga alat
if(isset($_POST['update_alat'])) {
    $id_alat = $_POST['id_alat'];
    $harga_alat = $_POST['harga_alat'];

    $query_update_alat = "UPDATE alat SET harga_alat='$harga_alat' WHERE id_alat=$id_alat";

    if (mysqli_query($conn, $query_update_alat)) {
        $message = "Harga alat berhasil diperbarui";
    } else {
        $message = "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manager</title>
    <link rel="stylesheet" href="dashboardmanager.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard Manager</h1>

        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

        <h2>Ruangan</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID Ruangan</th>
                        <th>Nama Ruangan</th>
                        <th>status</th>
                        <th>Kapasitas</th>
                        <th>Harga Ruangan (per jam)</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_ruangan = "SELECT * FROM ruangan";
                    $result_ruangan = mysqli_query($conn, $query_ruangan);

                    while ($row = mysqli_fetch_assoc($result_ruangan)) {
                        echo "<tr>";
                        echo "<form method='post' action=''>";
                        echo "<td>".$row['id_ruangan']."</td>";
                        echo "<td>".$row['nama_ruangan']."</td>";
                        echo "<td>".$row['status']."</td>";
                        echo "<td>".$row['kapasitas']."</td>";
                        echo "<td><input type='number' name='harga_ruangan' value='".$row['harga_ruangan']."' required></td>";
                        echo "<td>
                                <input type='hidden' name='id_ruangan' value='".$row['id_ruangan']."'>
                                <button type='submit' name='update_ruangan'>Update</button>
                              </td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2>Alat</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID Alat</th>
                        <th>Nama Alat</th>
                        <th>status</th>
                        <th>Stok Alat</th>
                        <th>Harga Alat</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_alat = "SELECT * FROM alat";
                    $result_alat = mysqli_query($conn, $query_alat);

                    while ($row = mysqli_fetch_assoc($result_alat)) {
                        echo "<tr>";
                        echo "<form method='post' action=''>";
                        echo "<td>".$row['id_alat']."</td>";
                        echo "<td>".$row['nama_alat']."</td>";
                        echo "<td>".$row['status']."</td>";
                        echo "<td>".$row['quantity']."</td>";
                        echo "<td><input type='number' name='harga_alat' value='".$row['harga_alat']."' required></td>";
                        echo "<td>
                                <input type='hidden' name='id_alat' value='".$row['id_alat']."'>
                                <button type='submit' name='update_alat'>Update</button>
                              </td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Form Logout -->
        <form method="post" action="loginmanager.php">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>

        <!-- Tombol untuk menuju laporan.php -->
        <form method="post" action="laporan.php">
            <button type="submit" class="report-button">Lihat Laporan</button>
        </form>
    </div>
</body>
</html>
