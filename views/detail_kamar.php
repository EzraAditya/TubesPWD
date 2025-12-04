<?php
session_start();
include '../actions/connection.php';

if (!isset($_GET['id'])) { header("Location: daftar_kamar.php"); exit; }
$id_kamar = $_GET['id'];

$query = mysqli_query($conn, "SELECT * FROM kamar WHERE id_kamar = '$id_kamar'");
$k = mysqli_fetch_assoc($query);

if (!$k) { echo "<script>alert('Kamar tidak ditemukan!'); window.location='daftar_kamar.php';</script>"; exit; }

// --- LOGIKA PENCARI GAMBAR GALERI ---
$keyword = strtolower($k['tipe_kamar']); // Misal: "single bed"
// Ambil semua file yang diawali nama kamar (contoh: single bed.jpeg, single bed3.jpeg)
$daftar_gambar = glob("../assets/image/" . $keyword . "*.jpeg");

// Gambar header pakai gambar pertama dari hasil pencarian
$gambar_header = (count($daftar_gambar) > 0) ? $daftar_gambar[0] : "https://via.placeholder.com/800x400?text=No+Image";

include '../includes/header.php';
?>

<style>
    .detail-header-img { width: 100%; height: 400px; object-fit: cover; border-radius: 12px; margin-bottom: 25px; }
    .facility-box { background: #f8f9fa; padding: 25px; border-radius: 12px; border: 1px solid #e9ecef; margin: 20px 0; }
    .btn-reservasi { background: #2c7be5; color: white; padding: 12px 40px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; font-size: 16px; }
    .btn-reservasi:hover { background: #1a5fbe; box-shadow: 0 4px 15px rgba(44, 123, 229, 0.4); }
</style>

<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2><?php echo $k['tipe_kamar']; ?> (No. <?php echo $k['nomor_kamar']; ?>)</h2>
        <h3 style="color:#2c7be5; margin:0;">Rp <?php echo number_format($k['harga']); ?> <span style="font-size:14px; color:#666; font-weight:normal;">/malam</span></h3>
    </div>

    <img src="<?php echo $gambar_header; ?>" alt="Foto Utama" class="detail-header-img" />

    <h3>Galeri Foto Kamar</h3>
    <div class="gallery-wrapper">
        <?php if(count($daftar_gambar) > 0): ?>
            <div class="gallery-scroll">
                <?php foreach($daftar_gambar as $foto): ?>
                    <div class="gallery-item">
                        <img src="<?php echo $foto; ?>" alt="Galeri" onclick="ubahGambarUtama('<?php echo $foto; ?>')" style="cursor:pointer;" />
                    </div>
                <?php endforeach; ?>
            </div>
            <p style="font-size:12px; color:#888; margin-top:5px;">*Geser ke samping untuk melihat foto lain, klik untuk memperbesar.</p>
        <?php else: ?>
            <div class="no-image">Belum ada foto tambahan untuk kamar ini.</div>
        <?php endif; ?>
    </div>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

    <div class="facility-box">
        <h3>Deskripsi & Fasilitas</h3>
        <p>Nikmati kenyamanan menginap di <strong><?php echo $k['tipe_kamar']; ?></strong> kami. Kamar ini terletak di lantai <strong><?php echo $k['lantai']; ?></strong> dengan kapasitas untuk <strong><?php echo $k['kapasitas']; ?> orang</strong>.</p>
        
        <h4 style="margin-top:20px;">Fasilitas Termasuk:</h4>
        <ul style="margin-left: 20px; line-height: 1.8;">
            <?php 
            // Memecah koma menjadi list (jika fasilitas dipisah koma)
            $fasilitas = explode(',', $k['fasilitas']);
            foreach($fasilitas as $f) {
                echo "<li>" . trim($f) . "</li>";
            }
            ?>
        </ul>
    </div>

    <div style="text-align: right; margin-top: 30px;">
        <a href="daftar_kamar.php" style="margin-right: 20px; color: #666; font-weight:600; text-decoration:none;">Kembali</a>
        <a href="booking.php?id=<?php echo $k['id_kamar']; ?>" class="btn-reservasi">Reservasi Sekarang</a>
    </div>
</div>

<script>
function ubahGambarUtama(src) {
    document.querySelector('.detail-header-img').src = src;
}
</script>

<?php include '../includes/footer.php'; ?>