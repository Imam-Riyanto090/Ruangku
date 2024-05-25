<?php
// process_loginoperator.php
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM OPERATOR WHERE USERNAME = '$username' AND PASSWORD = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Login success
    session_start();
    $_SESSION['username'] = $username;
    header("Location: dashboardoperator.php"); // Redirect to dashboard page
    exit();
} else {
    // Login failed
    echo "Login failed! Invalid email or password.";
}
$conn->close();
?>
