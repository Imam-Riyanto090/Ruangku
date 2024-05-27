<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];

    switch ($role) {
        case 'Operator':
            header("Location: loginoperator.php");
            exit;
        case 'Manajer':
            header("Location: loginmanager.php");
            exit;
        case 'Admin':
             header("Location: loginadmin.php");
             exit;
        default:
            // Tambahkan tindakan default atau pesan kesalahan di sini
            break;
    }
}
?>
