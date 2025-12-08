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
$id_user = $_SESSION['id_user'];

// 2. Ambil data reservasi
$query = "SELECT r.*, k.tipe_kamar FROM reservasi r 
          JOIN rincianreservasi rr ON r.id_reservasi = rr.id_reservasi 
          JOIN kamar k ON rr.id_kamar = k.id_kamar 
          WHERE r.id_reservasi = '$id_reservasi' 
          AND r.id_user = '$id_user' 
          AND r.status_reservasi = 'Selesai'"; // Hanya reservasi Selesai yang bisa diulas

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Pesanan tidak ditemukan atau belum selesai!'); window.location='riwayat.php';</script>";
    exit;
}

// 3. Cek apakah sudah pernah diulas
$check_ulasan = mysqli_query($conn, "SELECT id_ulasan FROM ulasan WHERE id_reservasi = '$id_reservasi'");
if (mysqli_num_rows($check_ulasan) > 0) {
    echo "<script>alert('Anda sudah memberikan ulasan untuk pesanan ini.'); window.location='riwayat.php';</script>";
    exit;
}


// 4. Proses Form Submit
if (isset($_POST['submit_ulasan'])) {
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);
    $tanggal_ulasan = date('Y-m-d');

    // Pastikan rating valid
    if ($rating >= 1 && $rating <= 5) {
        $insert_ulasan = "INSERT INTO ulasan (id_reservasi, rating, komentar, tanggal_ulasan) 
                          VALUES ('$id_reservasi', '$rating', '$komentar', '$tanggal_ulasan')";
        
        if (mysqli_query($conn, $insert_ulasan)) {
            echo "<script>alert('Terima kasih, ulasan berhasil disimpan!'); window.location='riwayat.php';</script>";
        } else {
            echo "<script>alert('Gagal menyimpan ulasan: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Rating tidak valid.');</script>";
    }
}

$pageTitle = "Beri Ulasan";
include '../includes/header.php';
?>

<div class="container" style="max-width: 600px; margin-top: 30px;">
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #fff;">
        <h2 style="margin-top: 0;">Berikan Ulasan Anda</h2>
        <hr>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <p style="margin: 5px 0;"><strong>Pesanan:</strong> #<?php echo $data['id_reservasi']; ?></p>
            <p style="margin: 5px 0;"><strong>Kamar:</strong> <?php echo $data['tipe_kamar']; ?></p>
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

            <button type="submit" name="submit_ulasan" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px;">
                Kirim Ulasan
            </button>
        </form>
        
        <br>
        <a href="riwayat.php" style="text-decoration: none; color: #666; display: block; text-align: center;">&laquo; Kembali</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>