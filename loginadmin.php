<?php
include 'config.php';
session_start();

// Hanya menampilkan pesan error jika belum login
$login_err_msg = isset($_SESSION['login_err_msg']) && !isset($_SESSION['user']) ? $_SESSION['login_err_msg'] : '';
unset($_SESSION['login_err_msg']); // Hapus pesan error setelah menampilkannya

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['user'] = $username; // Set session user
        header("Location: dashboardadmin.php");
    } else {
        $_SESSION['login_err_msg'] = "Username atau password salah!";
        header("Location: loginadmin.php");
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .error-message {
            color: red;
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1>Login Admin</h1>
        <p class="error-message"><?php echo $login_err_msg; ?></p> <!-- Tampilkan pesan kesalahan di sini -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <button class="back-button" onclick="window.location.href='index.php'">Pilih Role</button>
        <p class="signup-text">Belum punya akun? <a href="signupadmin.php">Sign up</a></p>
    </div>
</body>
</html>
