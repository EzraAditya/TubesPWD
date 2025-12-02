<?php
include '../actions/connection.php';
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }

$id_kamar = $_GET['id_kamar'];
$k = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kamar WHERE id_kamar='$id_kamar'"));

include '../includes/header.php';
?>
<div class="container">
    <h2>Booking: <?php echo $k['tipe_kamar']; ?></h2>
    <p>Harga: Rp <?php echo number_format($k['harga']); ?> /malam</p>
    
    <form action="../actions/reservation.php" method="post">
        <input type="hidden" name="id_kamar" value="<?php echo $id_kamar; ?>" />
        
        <label>Check In:</label>
        <input type="date" name="check_in" required="required" />
        
        <label>Check Out:</label>
        <input type="date" name="check_out" required="required" />
        
        <label>Jumlah Tamu:</label>
        <input type="number" name="jumlah_tamu" value="1" min="1" required="required" />
        
        <button type="submit" name="book_now">Konfirmasi Pesanan</button>
    </form>
<<<<<<< HEAD
    <a href="dashboard.php" class="logout" style="logout">Batal</a>

=======
    <a href="dashboard.php">Batal</a>
</div>
>>>>>>> a5954bddacddc889d12b2a9cf1b806f720e00a27
<?php include '../includes/footer.php'; ?>