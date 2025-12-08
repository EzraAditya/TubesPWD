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

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_telp = trim($_POST['no_telp']);
    $tgl = trim($_POST['tanggal_lahir']);
    $password = trim($_POST['password']);

    // =========================
    // VALIDASI FIELD
    // =========================

    if (empty($nama) || empty($email) || empty($no_telp) || empty($tgl) || empty($password)) {
        echo "<script>alert('Semua field wajib diisi!'); window.history.back();</script>";
        exit;
    }

    if (!str_contains($email, "@")) {
        echo "<script>alert('Email harus mengandung @'); window.history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!'); window.history.back();</script>";
        exit;
    }

    if (!preg_match('/^[0-9]+$/', $no_telp)) {
        echo "<script>alert('Nomor telepon harus angka!'); window.history.back();</script>";
        exit;
    }

    if (strlen($no_telp) < 8) {
        echo "<script>alert('Nomor telepon terlalu pendek!'); window.history.back();</script>";
        exit;
    }

    if (strlen($password) < 6) {
        echo "<script>alert('Password minimal 6 karakter!'); window.history.back();</script>";
        exit;
    }

    // CEK EMAIL SUDAH TERDAFTAR
    $check = mysqli_query($conn, "SELECT email FROM user WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email sudah digunakan!'); window.history.back();</script>";
        exit;
    }

    // HASH PASSWORD
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // SIMPAN KE DATABASE
    $sql = "INSERT INTO user (nama, email, no_telp, tanggal_lahir, password)
            VALUES ('$nama', '$email', '$no_telp', '$tgl', '$hash')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registrasi Berhasil!'); window.location='../views/login.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}


// --- LOGIKA LOGIN ---
if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);

    // =========================
    // VALIDASI FIELD
    // =========================
    if (empty($email) || empty($pass)) {
        echo "<script>alert('Email dan password wajib diisi!'); window.history.back();</script>";
        exit;
    }

    if (!str_contains($email, "@")) {
        echo "<script>alert('Email harus mengandung @'); window.history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!'); window.history.back();</script>";
        exit;
    }

    // =========================
    // CEK EMAIL DI DATABASE
    // =========================
    $result = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email'");

    if (mysqli_num_rows($result) !== 1) {
        echo "<script>alert('Email tidak ditemukan!'); window.history.back();</script>";
        exit;
    }

    $row = mysqli_fetch_assoc($result);

    // =========================
    // CEK PASSWORD
    // =========================
    if (!password_verify($pass, $row['password'])) {
        echo "<script>alert('Password salah!'); window.history.back();</script>";
        exit;
    }

    // =========================
    // LOGIN BERHASIL
    // =========================
    $_SESSION['id_user'] = $row['id_user'];
    $_SESSION['nama'] = $row['nama'];

    echo "<script>alert('Login Berhasil!'); window.location='../views/dashboard.php';</script>";
    exit;
}


// --- LOGIKA LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../views/login.php");
    exit;
}
?>