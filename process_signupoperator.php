<?php
// process_signupoperator.php
include 'db_configoperator.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$no_tlp = $_POST['no_tlp'];
$alamat = $_POST['alamat'];

$sql = "INSERT INTO OPERATOR (NAMA, EMAIL, PASSWORD, NO_TLP, ALAMAT) VALUES ('$nama', '$email', '$password', '$no_tlp', '$alamat')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    // Redirect to login page
    header("Location: loginoperator.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>