<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Sistem Penginapan</title>
    
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body>

<div class="auth-container">
    <h2>Registrasi</h2>
    <form action="../actions/connection.php" method="post">
        <label>Nama Lengkap:</label>
        <input type="text" name="nama" required="required" />
        
        <label>Email:</label>
        <input type="text" name="email" required="required" />
        
        <label>No Telp:</label>
        <input type="text" name="no_telp" />
        
        <label>Tanggal Lahir:</label>
        <input type="date" name="tanggal_lahir" />
        
        <label>Password:</label>
        <input type="password" name="password" required="required" />
        
        <button type="submit" name="register">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>