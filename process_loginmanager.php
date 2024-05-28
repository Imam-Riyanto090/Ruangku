<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the operator details from the database
    $query = "SELECT * FROM manager WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['nama'] = $row['nama']; // Assuming 'nama' is the column storing operator's name
        header("Location: dashboardmanager.php");
        exit();
    } else {
        $_SESSION['error'] = "Username atau Password salah!";
        header("Location: loginmanager.php");
        exit();
    }
}
?>
