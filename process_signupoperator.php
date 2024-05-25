<?php
include 'config.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$no_tlp = $_POST['no_tlp'];
$alamat = $_POST['alamat'];

$sql = "INSERT INTO OPERATOR (NAMA, USERNAME, PASSWORD, EMAIL, NO_TLP, ALAMAT) VALUES ('$nama', '$username', '$password', '$email', '$no_tlp', '$alamat')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Berhasil membuat akun');
            window.location.href='loginoperator.php';
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
