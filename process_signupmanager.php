<?php
include 'config.php';

$nama = $_POST['nama_manager'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$no_tlp = $_POST['no_tlp'];
$alamat = $_POST['alamat'];

// Validasi apakah username, email, dan nomor telepon sudah ada dalam database
$check_query = "SELECT * FROM MANAGER WHERE USERNAME='$username' OR EMAIL='$email' OR NO_TLP='$no_tlp'";
$check_result = $conn->query($check_query);

$sql = "INSERT INTO MANAGER (NAMA_MANAGER, USERNAME, PASSWORD, EMAIL, NO_TLP, ALAMAT) VALUES ('$nama_manager', '$username', '$password', '$email', '$no_tlp', '$alamat')";

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Berhasil membuat akun');
            window.location.href='loginmanager.php';
          </script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>