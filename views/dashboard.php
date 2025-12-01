<?php
// Panggil logic connection (session start ada di sana)
include '../actions/connection.php';
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }

include '../includes/header.php';

?>
<div class="container">
    <h3>Daftar Kamar</h3>
    <div class ="kamar-grid">
    <table border="1">
        <thead>
            <tr><th>Kamar</th><th>Harga</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php
            $q = mysqli_query($conn, "SELECT * FROM kamar");
            while($k = mysqli_fetch_assoc($q)){
            ?>
            <div class="kamar-card">
            <tr>
                <td><?php echo $k['tipe_kamar']; ?> (No. <?php echo $k['nomor_kamar']; ?>)</td>
                <td>Rp <?php echo number_format($k['harga']); ?></td>
                <td>
                    <div style="display:flex;gap:8px;">
                    <a href="booking.php?id=<?php echo $k['id_kamar']; ?>">Booking</a> | 
                    <a href="../actions/wishlist.php?add=<?php echo $k['id_kamar']; ?>" style="color:green;">+ Wishlist</a>
                    </div>
                </td>
            </tr>
            </div>
            <?php } ?>
        </tbody>
    </table>
    </div>

    <br /><hr /><br />

    <h3>Wishlist Saya</h3>
    <table border="1">
        <thead><tr><th>Kamar</th><th>Harga</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php
            $id = $_SESSION['id_user'];
            $qw = mysqli_query($conn, "SELECT w.id_wishlist, k.nomor_kamar, k.harga FROM wishlist w JOIN kamar k ON w.id_kamar = k.id_kamar WHERE w.id_user='$id'");
            while($w = mysqli_fetch_assoc($qw)){
            ?>
            <tr>
                <td>No. <?php echo $w['nomor_kamar']; ?></td>
                <td>Rp <?php echo number_format($w['harga']); ?></td>
                <td>
                    <a href="../actions/wishlist.php?delete=<?php echo $w['id_wishlist']; ?>" onclick="return confirm('Hapus?')" style="color:red;">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>