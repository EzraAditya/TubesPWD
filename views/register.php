<?php include '../includes/header.php'; ?>

<style>
    body {
        background: url('../assets/bg-hotel.jpg') center/cover no-repeat fixed;
        font-family: 'Poppins', sans-serif;
    }

    .auth-container {
        width: 420px;
        margin: 60px auto;
        background: rgba(255, 255, 255, 0.92);
        padding: 30px 40px;
        border-radius: 18px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.4s ease-in-out;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
        font-weight: 600;
    }

    label {
        display: block;
        margin: 10px 0 6px;
        font-size: 14px;
        color: #444;
    }

    input {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    button {
        width: 100%;
        margin-top: 20px;
        padding: 12px;
        background: #3a7bd5;
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 15px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background: #336dc2;
    }

    .auth-container p {
        text-align: center;
        margin-top: 15px;
    }

    a {
        color: #3a7bd5;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(10px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>

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