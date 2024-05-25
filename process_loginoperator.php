<?php
// process_loginoperator.php
include 'config.php';

$Username = $_POST['username'];
$Password = $_POST['password'];

$sql = "SELECT * FROM OPERATOR WHERE Username = '$Username' AND Password = '$Password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Login success
    echo "Login successful! Welcome, " . $username;
    // Redirect to operator page or dashboard
} else {
    // Login failed
    echo "Login failed! Invalid email or password.";
}
$conn->close();
?>