<?php
session_start();
include '../actions/connection.php';

// 1. Cek ID Kamar
if (!isset($_GET['id'])) { header("Location: daftar_kamar.php"); exit; }
$id_kamar = $_GET['id'];

// 2. Ambil Data Kamar dari Database
$query = mysqli_query($conn, "SELECT * FROM kamar WHERE id_kamar = '$id_kamar'");
$k = mysqli_fetch_assoc($query);

// Jika kamar tidak ada
if (!$k) { echo "<script>alert('Kamar tidak ditemukan!'); window.location='daftar_kamar.php';</script>"; exit; }

// 3. LOGIKA CARI GAMBAR OTOMATIS (GLOB)
$keyword = strtolower($k['tipe_kamar']); 

$daftar_gambar = glob("../assets/image/" . $keyword . "*.jpeg");

$gambar_header = (count($daftar_gambar) > 0) ? $daftar_gambar[0] : "https://via.placeholder.com/800x400?text=No+Image";

include '../includes/header.php';
?>

<style>
    .detail-header-img { 
        width: 100%; 
        height: 450px; 
        object-fit: cover; 
        border-radius: 12px; 
        margin-bottom: 25px; 
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .facility-box { 
        background: #fff; 
        padding: 30px; 
        border-radius: 12px; 
        border: 1px solid #e0e0e0; 
        margin-top: 30px;
    }
</style>

<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h2 style="margin:0; font-size: 28px;"><?php echo $k['tipe_kamar']; ?></h2>
            <span style="color:#666; font-size:14px;">Nomor Kamar: <?php echo $k['nomor_kamar']; ?></span>
        </div>
        <div style="text-align:right;">
            <h3 style="color:#2c7be5; margin:0; font-size: 24px;">Rp <?php echo number_format($k['harga']); ?></h3>
            <span style="font-size:13px; color:#888;">/ malam</span>
        </div>
    </div>

    <img id="mainImage" src="<?php echo $gambar_header; ?>" alt="Foto Utama" class="detail-header-img" />

    <h3 style="margin-bottom: 15px;">Galeri Foto</h3>
    
    <div class="gallery-wrapper">
        <?php if(count($daftar_gambar) > 0): ?>
            
            <div class="gallery-scroll">
                <?php foreach($daftar_gambar as $foto): ?>
                    <div class="gallery-item" onclick="gantiGambar('<?php echo $foto; ?>')">
                        <img src="<?php echo $foto; ?>" alt="Galeri Kamar" />
                    </div>
                <?php endforeach; ?>
            </div>
            
            <p style="font-size:13px; color:#888; margin-top:8px; font-style:italic;">
                <i class="icon-info"></i> Geser ke samping untuk melihat foto lain. Klik foto untuk memperbesar.
            </p>

        <?php else: ?>
            <div style="padding: 20px; background: #eee; border-radius: 8px; text-align: center; color: #666;">
                Belum ada foto tambahan untuk kamar ini.
            </div>
        <?php endif; ?>
    </div>

    <div class="facility-box">
        <h3>Deskripsi & Fasilitas</h3>
        <p style="line-height: 1.8; color: #444; margin-bottom: 20px;">
            Nikmati kenyamanan maksimal di <strong><?php echo $k['tipe_kamar']; ?></strong>. 
            Kamar ini terletak di lantai <strong><?php echo $k['lantai']; ?></strong> dengan desain modern yang menenangkan.
            Sangat cocok untuk kapasitas hingga <strong><?php echo $k['kapasitas']; ?> orang</strong>.
        </p>
        
        <h4 style="margin-bottom: 10px;">Fasilitas Termasuk:</h4>
        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
            <?php 
            // Memecah fasilitas berdasarkan koma
            $fasilitas = explode(',', $k['fasilitas']);
            foreach($fasilitas as $f) {
                echo '<span style="background:#eef2ff; color:#2c7be5; padding:6px 15px; border-radius:20px; font-size:13px; font-weight:600;">✓ '.trim($f).'</span>';
            }
            ?>
        </div>
    </div>

    <div style="margin-top: 40px; display: flex; justify-content: flex-end; gap: 15px;">
        <a href="daftar_kamar.php" style="padding: 12px 25px; border: 1px solid #ccc; border-radius: 8px; color: #555; text-decoration: none; font-weight: 600;">Kembali</a>
        <a href="booking.php?id=<?php echo $k['id_kamar']; ?>" style="background: #2c7be5; color: white; padding: 12px 40px; border-radius: 8px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 15px rgba(44, 123, 229, 0.3);">Reservasi Sekarang</a>
    </div>
</div>

<script>
    function gantiGambar(urlGambar) {
        var img = document.getElementById('mainImage');
        img.style.opacity = 0.5;
        
        setTimeout(function(){
            img.src = urlGambar;
            img.style.opacity = 1;
        }, 200);
    }
</script>

<?php include '../includes/footer.php'; ?>