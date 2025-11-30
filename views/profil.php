<?php
include '../actions/connection.php';
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }

// LOGIC UPDATE PROFIL LANGSUNG DISINI AGAR SIMPLE
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['no_telp'];
    $tgl = $_POST['tanggal_lahir'];
    $id = $_SESSION['id_user'];
    
    mysqli_query($conn, "UPDATE user SET nama='$nama', no_telp='$telp', tanggal_lahir='$tgl' WHERE id_user='$id'");
    $_SESSION['nama'] = $nama;
    echo "<script>alert('Update Berhasil');</script>";
}

$id = $_SESSION['id_user'];
$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user='$id'"));

include '../includes/header.php';
include '../includes/navbar.php';
?>

    <h2>Edit Profil</h2>
    <form action="" method="post">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?php echo $d['nama']; ?>" required="required" />
        
        <label>Email:</label>
        <input type="text" value="<?php echo $d['email']; ?>" disabled="disabled" style="background:#eee;" />
        
        <label>No Telp:</label>
        <input type="text" name="no_telp" value="<?php echo $d['no_telp']; ?>" />
        
        <label>Tanggal Lahir:</label>
        <input type="date" name="tanggal_lahir" value="<?php echo $d['tanggal_lahir']; ?>" />
        
        <button type="submit" name="update">Simpan Perubahan</button>
    </form>

<?php include '../includes/footer.php'; ?>