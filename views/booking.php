<?php
if (!isset($_SESSION)) { session_start(); }

include '../actions/connection.php';
// reservation.php dipanggil untuk memproses logika booking
include '../actions/reservation.php';

// Cek Login
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }

// Cek apakah ID Kamar ada di URL
if (!isset($_GET['id'])) { header("Location: dashboard.php"); exit; }

$id_kamar = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM kamar WHERE id_kamar='$id_kamar'");
$k = mysqli_fetch_assoc($query);

// Jika kamar tidak valid
if (!$k) { header("Location: dashboard.php"); exit; }

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
        
        <div style="display:flex; justify-content:space-between; margin-top:10px;">
            <a href="dashboard.php">Batal</a>
            <button class="btn-primary" type="submit" name="book_now">Konfirmasi Pesanan</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>