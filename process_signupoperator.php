<?php
// Database connection
include 'config.php';

// Check connection
// Check if 'no_telp' is set in the POST request
if (isset($_POST['no_tlp'])) {
    $no_telp = $_POST['no_tlp'];
} else {
    die("Phone number is required.");
}

// Other variables from the form
// Assuming other form fields like name, email, etc. are also being passed
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Insert into the database
$query = "INSERT INTO operator (nama, email, username, password, no_tlp) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($query);   
$stmt->bind_param("ssss", $name, $email, $username, $password, $no_tlp);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
