<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operator</title>
    <link rel="stylesheet" href="styleoperator.css">
</head>
<body>
    <div class="container">
        <h1>Login Operator</h1>
        <form action="process_loginoperator.php" method="POST">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p class="signup-text">Belum punya akun? <a href="signupoperator.php">Sign up</a></p>
    </div>
</body>
</html>