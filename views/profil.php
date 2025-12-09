<?php
session_start(); // Pastikan session dimulai
include '../actions/connection.php';

// Cek Login
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }

// --- LOGIKA UPDATE PROFIL ---
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['no_telp'];
    $tgl  = $_POST['tanggal_lahir'];
    $id   = $_SESSION['id_user'];
    
    // Update Database
    $query = "UPDATE user SET nama='$nama', no_telp='$telp', tanggal_lahir='$tgl' WHERE id_user='$id'";
    
    if(mysqli_query($conn, $query)) {
        $_SESSION['nama'] = $nama; // Update session nama biar header berubah
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($conn) . "');</script>";
    }
}

// Ambil Data User Terbaru
$id = $_SESSION['id_user'];
$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id'"));

$pageStyles = '<link rel="stylesheet" href="../assets/css/profil.css">';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

include '../includes/header.php';
?>

<div class="container profile-container">
    <h2 class="page-title">Edit Profil</h2>

    <form action="" method="post" class="profile-form">
        
        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?php echo $d['nama']; ?>" required />
        
        <label>Email (Tidak dapat diubah)</label>
        <input type="text" value="<?php echo $d['email']; ?>" disabled class="disabled-input" />
        
        <label>Nomor Telepon</label>
        <input type="text" name="no_telp" value="<?php echo $d['no_telp']; ?>" />
        
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="<?php echo $d['tanggal_lahir']; ?>" />
        
        <button type="submit" name="update" class="btn-primary">Simpan Perubahan</button>
        <button type="submit" name="logout" class="btn-danger" onclick="return confirm('Yakin ingin logout?')">Logout</button>
        <button type="button" class="btn-danger" onclick="hapusAkun()">Hapus Akun</button>

    </form>
</div>

<?php include '../includes/footer.php'; ?>