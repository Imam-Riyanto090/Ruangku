<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];

    switch ($role) {
        case 'Operator':
            header("Location: loginoperator.php");
            exit;
        case 'Manajer':
            // Tambahkan kode pengal    ihan ke halaman manajer di sini, misalnya:
            // header("Location: loginmanajer.php");
            // exit;
            break;
        case 'Admin':
            // Tambahkan kode pengalihan ke halaman admin di sini, misalnya:
             header("Location: loginadmin.php");
             exit;
        default:
            // Tambahkan tindakan default atau pesan kesalahan di sini
            break;
    }
}
?>
