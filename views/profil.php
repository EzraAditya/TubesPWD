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

$pageStyles = '<link rel="stylesheet" href="/assets/css/profil.css">';

include '../includes/header.php';

?>

<div class="container profile-container">
    <h2 class="page-title">Edit Profil</h2>

    <form action="" method="post" class="profile-form3">
        <br>
        <label>Nama:</label>
        <input type="text" name="nama" value="<?php echo $d['nama']; ?>" required="required" />
        
        <label>Email:</label>
        <input type="text" value="<?php echo $d['email']; ?>" disabled="disabled" style="background:#eee;" />
        
        <label>No Telp:</label>
        <input type="text" name="no_telp" value="<?php echo $d['no_telp']; ?>" />
        
        <label>Tanggal Lahir:</label>
        <input type="date" name="tanggal_lahir" value="<?php echo $d['tanggal_lahir']; ?>" />
        
        <button type="submit" name="update" class="btn-primary">Simpan Perubahan</button>
        <a href="../actions/connection.php?logout=true" class="logout">Logout</a>
        <button type="button" class="btn-danger" onclick="hapusAkun()">Hapus Akun</button>

    </form>
</div>
<script>
function hapusAkun() {
    if (!confirm("Yakin ingin menghapus akun Anda? Semua data akan hilang!")) return;

    fetch("../actions/backend_user/user/delete_user.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            id_user: <?php echo $_SESSION['id_user']; ?>
        })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);

        if (data.status) {
            window.location = "register.php"; // redirect setelah sukses hapus
        }
    })
    .catch(err => {
        alert("Terjadi kesalahan pada server");
    });
}
</script>

<?php include '../includes/footer.php'; ?>