<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];

    switch ($role) {
        case 'Operator':
            header("Location: loginoperator.php");
            exit;
        case 'Manajer':
            header('location: manajer.php');
            exit;
        case 'Admin':

            break;
        default:

            break;
    }
}
?>
