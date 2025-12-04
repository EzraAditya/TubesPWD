<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penginapan</title>
    
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

<div class="auth-container">
    <h2>Login Akun</h2>
    <form action="../actions/connection.php" method="post">
        <label>Email:</label>
        <input type="text" name="email" required="required" />
        
        <label>Password:</label>
        <input type="password" name="password" required="required" />
        
        <button type="submit" name="login">Masuk</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>