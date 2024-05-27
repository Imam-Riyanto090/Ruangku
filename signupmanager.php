<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Manager</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="container">
        <h1>Signup Manager</h1>
        <form action="process_signupmanager.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p class="signup-text">Sudah punya akun? <a href="loginmanager.php">Login</a></p>
    </div>
</body>
</html>
