<?php include '../includes/header.php'; ?>

    <h2>Login</h2>
    <form action="../actions/connection.php" method="post">
        <label>Email:</label>
        <input type="text" name="email" required="required" />
        
        <label>Password:</label>
        <input type="password" name="password" required="required" />
        
        <button type="submit" name="login">Masuk</button>
    </form>
    <p>Belum punya akun? <a href="registed.php">Daftar disini</a></p>

<?php include '../includes/footer.php'; ?>