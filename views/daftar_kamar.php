<?php
session_start();
include '../actions/connection.php';

// Fungsi untuk mencari gambar pertama yang cocok
function cariGambarUtama($tipe_kamar) {
    // Bersihkan nama kamar (huruf kecil semua) contoh: "Single Bed" jadi "single bed"
    $keyword = strtolower($tipe_kamar);
    
    // Cari semua file .jpeg yang mengandung nama kamar di folder image
    // Path menggunakan "../" karena kita ada di folder views
    $files = glob("../assets/image/" . $keyword . "*.jpeg");
    
    // Jika ketemu minimal 1, ambil yang pertama
    if (count($files) > 0) {
        return $files[0];
    } else {
        // Gambar cadangan jika tidak ada yang cocok
        return "https://via.placeholder.com/500x300?text=No+Image"; 
    }
}

if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }
include '../includes/header.php';
?>

<div class="container">
    <h2>Pilihan Kamar Kami</h2>
    <p>Temukan kenyamanan terbaik sesuai kebutuhan Anda.</p>

    <div class="room-grid">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM kamar");
        while($kamar = mysqli_fetch_assoc($query)) {
            // Panggil fungsi pencari gambar otomatis
            $gambarUtama = cariGambarUtama($kamar['tipe_kamar']);
        ?>
        
        <div class="room-card">
            <img src="<?php echo $gambarUtama; ?>" alt="<?php echo $kamar['tipe_kamar']; ?>" class="room-img" />
            
            <div class="room-content">
                <div class="room-title"><?php echo $kamar['tipe_kamar']; ?></div>
                <div class="room-price">Rp <?php echo number_format($kamar['harga']); ?> / malam</div>
                
                <p class="room-desc-short">
                    Kapasitas: <?php echo $kamar['kapasitas']; ?> Orang<br>
                    <?php echo substr($kamar['fasilitas'], 0, 50); ?>...
                </p>
                
                <a href="detail_kamar.php?id=<?php echo $kamar['id_kamar']; ?>" class="btn-detail">Lihat Detail & Foto</a>
            </div>
        </div>
        
        <?php } ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>