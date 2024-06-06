<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="container">
        <h1>Sign Up</h1>
        <form action="process_signupoperator.php" method="POST">
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="no_tlp" placeholder="No Telp" required>
            <input type="text" name="alamat" placeholder="Alamat" required>
            <button type="submit">Sign Up</button>
        </form>
        <p class="signup-text">Sudah punya akun? <a href="loginadmin.php">Login</a></p>
    </div>
</body>
</html>
