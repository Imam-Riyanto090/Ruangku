<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operator</title>
    <link rel="stylesheet" href="styleoperator.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Login Operator</h1>
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p class='error'>Invalid username or password</p>";
        }
        ?>
        <form action="process_loginoperator.php" method="POST">
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
        <p class="signup-text">Belum punya akun? <a href="signupoperator.php">Sign up</a></p>
    </div>
</body>
</html>
