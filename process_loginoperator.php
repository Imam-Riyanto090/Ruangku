<?php
// process_loginoperator.php
include 'db_configoperator.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM OPERATOR WHERE EMAIL = '$email' AND PASSWORD = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Login success
    echo "Login successful! Welcome, " . $email;
    // Redirect to operator page or dashboard
} else {
    // Login failed
    echo "Login failed! Invalid email or password.";
}
$conn->close();
?>