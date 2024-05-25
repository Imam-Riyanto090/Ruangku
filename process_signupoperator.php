<?php
include 'config.php';

$nama = $_POST['nama'];
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$no_telp = $_POST['no_telp'];
$alamat = $_POST['alamat'];

<<<<<<< HEAD
// Validasi apakah username, email, dan nomor telepon sudah ada dalam database
$check_query = "SELECT * FROM OPERATOR WHERE USERNAME='$username' OR EMAIL='$email' OR NO_TLP='$no_tlp'";
$check_result = $conn->query($check_query);
=======
$sql = "INSERT INTO OPERATOR (NAMA, USERNAME, PASSWORD, EMAIL, NO_TELP, ALAMAT) VALUES ('$nama', '$username', '$password', '$email', '$no_telp', '$alamat')";
>>>>>>> a554ce1ef0ef9aac1b359de2894192d3943eb563

if ($check_result->num_rows > 0) {
    // Jika data sudah ada, tampilkan pesan kesalahan
    echo "<script>
            alert('Username, email, atau nomor telepon sudah digunakan. Silakan gunakan yang lain.');
            window.location.href='signupoperator.php';
          </script>";
} else {
    // Jika tidak ada, lakukan operasi INSERT INTO
    $sql = "INSERT INTO OPERATOR (NAMA, USERNAME, PASSWORD, EMAIL, NO_TLP, ALAMAT) VALUES ('$nama', '$username', '$password', '$email', '$no_tlp', '$alamat')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Berhasil membuat akun');
                window.location.href='loginoperator.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
