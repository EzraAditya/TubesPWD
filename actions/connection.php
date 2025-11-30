<?php
// Cek session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "dbtubes_pwd"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// --- LOGIKA REGISTER ---
if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];
    $tgl = $_POST['tanggal_lahir'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (nama, email, no_telp, tanggal_lahir, password) VALUES ('$nama', '$email', '$no_telp', '$tgl', '$pass')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registrasi Berhasil!'); window.location='../views/login.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}

// --- LOGIKA LOGIN ---
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($pass, $row['password'])) {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['nama'] = $row['nama'];
            header("Location: ../views/dashboard.php");
            exit;
        }
    }
    echo "<script>alert('Email atau Password Salah!'); window.location='../views/login.php';</script>";
}

// --- LOGIKA LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../views/login.php");
    exit;
}
?>