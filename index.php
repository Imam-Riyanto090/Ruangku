<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Role</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
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
