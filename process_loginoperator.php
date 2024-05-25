<?php
include 'config.php';
session_start();

<<<<<<< HEAD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the operator details from the database
    $query = "SELECT * FROM operator WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
=======
$Username = $_POST['username'];
$Password = $_POST['password'];

$sql = "SELECT * FROM OPERATOR WHERE Username = '$Username' AND Password = '$Password'";
$result = $conn->query($sql);
>>>>>>> a554ce1ef0ef9aac1b359de2894192d3943eb563

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['nama'] = $row['nama']; // Assuming 'nama' is the column storing operator's name
        header("Location: dashboardoperator.php");
        exit();
    } else {
        echo "Invalid login credentials";
    }
}
?>