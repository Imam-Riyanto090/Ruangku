<?php
// process_loginoperator.php
include 'config.php';

<<<<<<< HEAD
<<<<<<< HEAD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch the operator details from the database
    $query = "SELECT * FROM operator WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
=======
$Username = $_POST['username'];
$Password = $_POST['password'];

$sql = "SELECT * FROM OPERATOR WHERE Username = '$Username' AND Password = '$Password'";
$result = $conn->query($sql);
>>>>>>> a554ce1ef0ef9aac1b359de2894192d3943eb563
=======
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM OPERATOR WHERE USERNAME = '$username' AND PASSWORD = '$password'";
$result = $conn->query($sql);
>>>>>>> parent of 755c2d1 (membuat dashboardoperator dan fungsi lainnya)

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
