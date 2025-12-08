<?php
session_start();
include '../actions/connection.php';

// 1. Cek Autentikasi dan ID Reservasi
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
if (!isset($_GET['id'])) {
    header("Location: riwayat.php");
    exit;
}

$id_reservasi = $_GET['id'];
$id_user = $_SESSION['id_user']; // id_user dari sesi
$id_kamar = null; // Akan diambil dari reservasi

// 2. Ambil data reservasi dan ID KAMAR
// Hanya reservasi yang 'Selesai' dan milik user yang bisa diulas
$query_reservasi = "SELECT r.*, rr.id_kamar, k.tipe_kamar 
          FROM reservasi r 
          JOIN rincianreservasi rr ON r.id_reservasi = rr.id_reservasi 
          JOIN kamar k ON rr.id_kamar = k.id_kamar 
          WHERE r.id_reservasi = '$id_reservasi' 
          AND r.id_user = '$id_user' 
          AND r.status_reservasi IN ('Confirmed', 'Selesai')"; // Asumsi hanya 'Selesai' yang bisa diulas

$result_reservasi = mysqli_query($conn, $query_reservasi);
$data_reservasi = mysqli_fetch_assoc($result_reservasi);

if (!$data_reservasi) {
    echo "<script>alert('Pesanan tidak dapat diulas karena tidak ditemukan atau belum selesai!'); window.location='riwayat.php';</script>";
    exit;
}

$id_kamar = $data_reservasi['id_kamar']; // Ambil ID Kamar yang direservasi

// 3. Cek apakah sudah pernah di-review berdasarkan id_user dan id_kamar
// Karena tabel 'review' tidak punya id_reservasi, kita cek kombinasi user/kamar
$check_review = mysqli_query($conn, "SELECT id_review FROM review WHERE id_user = '$id_user' AND id_kamar = '$id_kamar'");
if (mysqli_num_rows($check_review) > 0) {
    echo "<script>alert('Anda sudah pernah memberikan review untuk kamar ini.'); window.location='riwayat.php';</script>";
    exit;
}


// 4. Proses Form Submit
if (isset($_POST['submit_review'])) {
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);
    
    // Pastikan rating valid (1-5)
    if ($rating >= 1 && $rating <= 5) {
        
        // --- INSERT KE TABEL 'review' ---
        // Menggunakan id_user dan id_kamar sesuai struktur tabel Anda
        $insert_review = "INSERT INTO review (id_user, id_kamar, rating, komentar) 
                          VALUES ('$id_user', '$id_kamar', '$rating', '$komentar')";
        
        if (mysqli_query($conn, $insert_review)) {
            // OPTIONAL: Anda bisa update kolom di reservasi/rincianreservasi agar tidak bisa di-review lagi
            
            echo "<script>alert('Terima kasih, review berhasil disimpan!'); window.location='riwayat.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan review: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Rating tidak valid.');</script>";
    }
}

$pageTitle = "Beri Review";
include '../includes/header.php';
?>

<div class="container" style="max-width: 600px; margin-top: 30px;">
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #fff;">
        <h2 style="margin-top: 0;">Berikan Review Anda</h2>
        <hr>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <p style="margin: 5px 0;"><strong>Pesanan:</strong> #<?php echo $data_reservasi['id_reservasi']; ?></p>
            <p style="margin: 5px 0;"><strong>Kamar:</strong> <?php echo $data_reservasi['tipe_kamar']; ?></p>
        </div>

        <form action="" method="post">
            <div style="margin-bottom: 15px;">
                <label for="rating" style="display: block; margin-bottom: 5px; font-weight: bold;">Rating (Bintang):</label>
                <select name="rating" id="rating" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="">-- Pilih Rating --</option>
                    <option value="5">⭐⭐⭐⭐⭐ Sangat Baik</option>
                    <option value="4">⭐⭐⭐⭐ Baik</option>
                    <option value="3">⭐⭐⭐ Cukup</option>
                    <option value="2">⭐⭐ Kurang</option>
                    <option value="1">⭐ Buruk</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="komentar" style="display: block; margin-bottom: 5px; font-weight: bold;">Komentar:</label>
                <textarea name="komentar" id="komentar" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; resize: vertical;"></textarea>
            </div>

            <button type="submit" name="submit_review" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px;">
                Kirim Review
            </button>
        </form>
        
        <br>
        <a href="riwayat.php" style="text-decoration: none; color: #666; display: block; text-align: center;">&laquo; Kembali</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>