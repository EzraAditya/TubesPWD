<?php
session_start();
include '../actions/connection.php';

// Cek Login
if (!isset($_SESSION['id_user'])) { 
    header("Location: login.php"); 
    exit; 
}

// Cek apakah ada ID Reservasi di URL
if (!isset($_GET['id'])) {
    header("Location: riwayat.php"); // Redirect jika tidak ada ID
    exit;
}

$id_reservasi = $_GET['id'];
$id_user = $_SESSION['id_user'];

// Ambil data reservasi gabung dengan tabel kamar dan rincian
// Kita perlu harga kamar untuk menghitung ulang nanti, dan data tanggal lama
$query = "SELECT r.*, rr.id_kamar, k.tipe_kamar, k.harga 
          FROM reservasi r 
          JOIN rincianreservasi rr ON r.id_reservasi = rr.id_reservasi 
          JOIN kamar k ON rr.id_kamar = k.id_kamar 
          WHERE r.id_reservasi = '$id_reservasi' AND r.id_user = '$id_user'";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Jika data tidak ditemukan (atau user mencoba edit punya orang lain)
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='riwayat.php';</script>";
    exit;
}

include '../includes/header.php';
?>

<div class="container">
    <h2>Edit Booking: <?php echo $data['tipe_kamar']; ?></h2>
    <p>Harga: Rp <?php echo number_format($data['harga']); ?> /malam</p>
    
    <form action="../actions/reservation.php" method="post">
        
        <input type="hidden" name="id_reservasi" value="<?php echo $data['id_reservasi']; ?>" />
        
        <input type="hidden" name="id_kamar" value="<?php echo $data['id_kamar']; ?>" />
        <input type="hidden" name="harga_per_malam" value="<?php echo $data['harga']; ?>" />

        <label>Check In:</label>
        <input type="date" name="check_in" required="required" value="<?php echo $data['check_in']; ?>" />
        
        <label>Check Out:</label>
        <input type="date" name="check_out" required="required" value="<?php echo $data['check_out']; ?>" />
        
        <label>Jumlah Tamu:</label>
        <input type="number" name="jumlah_tamu" min="1" required="required" value="<?php echo isset($data['jumlah_tamu']) ? $data['jumlah_tamu'] : 1; ?>" />
        
        <div style="display:flex; justify-content:space-between; margin-top:10px;">
            <a href="riwayat.php" style="text-decoration:none; padding:10px; color:black;">Batal</a>
            
            <button class="btn-primary" type="submit" name="update_booking">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>