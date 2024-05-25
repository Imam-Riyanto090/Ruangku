<?php
// process_signupoperator.php
include 'config.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$no_telp = $_POST['no_telp'];
$alamat = $_POST['alamat'];

$sql = "INSERT INTO OPERATOR (NAMA, USERNAME, PASSWORD, EMAIL, NO_TELP, ALAMAT) VALUES ('$nama', '$username', '$password', '$email', '$no_telp', '$alamat')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    // Redirect to login page
    header("Location: loginoperator.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>