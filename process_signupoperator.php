<?php
include 'config.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$no_telp = $_POST['no_telp'];
$alamat = $_POST['alamat'];

// Validasi apakah username, email, dan nomor telepon sudah ada dalam database
$check_query = "SELECT * FROM OPERATOR WHERE USERNAME='$username' OR EMAIL='$email' OR NO_TLP='$no_tlp'";
$check_result = $conn->query($check_query);

$sql = "INSERT INTO OPERATOR (NAMA, USERNAME, PASSWORD, EMAIL, NO_TELP, ALAMAT) VALUES ('$nama', '$username', '$password', '$email', '$no_telp', '$alamat')";

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