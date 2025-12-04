<?php
session_start();
include '../actions/connection.php'; 

if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }

include '../includes/header.php';
?>

<div class="container">
    <h2>Pilihan Kamar Kami</h2>
    <p>Temukan kenyamanan terbaik untuk istirahat Anda.</p>

    <div class="room-grid">
        <?php
        // Ambil data kamar dari database
        $query = mysqli_query($conn, "SELECT * FROM kamar");
        
        while($kamar = mysqli_fetch_assoc($query)) {
            // Gambar Random (karena belum ada kolom foto di DB)
            $gambar = "https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=500&q=80";
        ?>
        
        <div class="room-card">
            <img src="<?php echo $gambar; ?>" alt="Kamar" class="room-img" />
            
            <div class="room-content">
                <div class="room-title"><?php echo $kamar['tipe_kamar']; ?></div>
                <div class="room-price">Rp <?php echo number_format($kamar['harga']); ?> / malam</div>
                
                <p class="room-desc-short">
                    Fasilitas: <?php echo $kamar['fasilitas']; ?><br>
                    Lantai: <?php echo $kamar['lantai']; ?> | Kapasitas: <?php echo $kamar['kapasitas']; ?> Orang
                </p>
                
                <a href="detail_kamar.php?id=<?php echo $kamar['id_kamar']; ?>" class="btn-detail">Lihat Detail & Booking</a>
            </div>
        </div>
        
        <?php } ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>