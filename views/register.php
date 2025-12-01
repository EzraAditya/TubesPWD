<?php include '../includes/header.php'; ?>
<div class="center-box">
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