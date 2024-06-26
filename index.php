<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Role</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="welcome-title">Selamat datang di Ruangku</h1>
            <p class="welcome-description">Aplikasi untuk sewa ruang meeting</p>
        </div>
        <h1>Pilih Role Anda</h1>
        <div class="button-container">
            <form action="pilih-role.php" method="post">
                <button type="submit" name="role" value="Operator">Operator</button>
                <button type="submit" name="role" value="Manajer">Manajer</button>
                <button type="submit" name="role" value="Admin">Admin</button>
            </form>
        </div>
    </div>
</body>
</html>
