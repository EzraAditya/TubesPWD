<?php
session_start();
// Pastikan path connection benar. Jika file ini di folder 'views', maka '../actions/' benar.
include '../actions/connection.php'; 

// 1. Cek Login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

// 2. Cek ID di URL (misal: edit_review.php?id=5)
// Jika tidak ada ID di URL, kembalikan ke halaman riwayat
if (!isset($_GET['id'])) {
    // PASTIKAN NAMA FILE INI BENAR (sesuaikan dengan nama file riwayat Anda)
    header("Location: riwayat_review.php"); 
    exit;
}

$id_review_get = $_GET['id'];
$id_user       = $_SESSION['id_user'];

// 3. PROSES UPDATE (Dijalankan saat tombol Simpan diklik)
if (isset($_POST['update_review'])) {
    // Ambil data dari form
    $id_review_post = $_POST['id_review']; // Ambil dari hidden input (lebih aman)
    $rating_baru    = $_POST['rating'];
    $komentar_baru  = mysqli_real_escape_string($conn, $_POST['komentar']);

    // Query Update
    $query_update = "UPDATE review 
                     SET rating = '$rating_baru', komentar = '$komentar_baru' 
                     WHERE id_review = '$id_review_post' AND id_user = '$id_user'";

    if (mysqli_query($conn, $query_update)) {
        echo "<script>
                alert('Review berhasil diperbarui!');
                // PASTIKAN NAMA FILE DI BAWAH INI SESUAI
                window.location.href = 'riwayat_review.php'; 
              </script>";
        exit;
    } else {
        $error = "Gagal mengupdate database: " . mysqli_error($conn);
    }
}

// 4. AMBIL DATA LAMA (Untuk mengisi form)
$query_get = "SELECT r.*, k.tipe_kamar 
              FROM review r 
              JOIN kamar k ON r.id_kamar = k.id_kamar 
              WHERE r.id_review = '$id_review_get' AND r.id_user = '$id_user'";

$result_get = mysqli_query($conn, $query_get);

if (mysqli_num_rows($result_get) == 0) {
    echo "<script>alert('Data tidak ditemukan atau Anda tidak berhak mengedit.'); window.location='riwayat_review.php';</script>";
    exit;
}

$data_review = mysqli_fetch_assoc($result_get);
?>

<?php include '../includes/header.php'; ?>

<div class="container" style="max-width: 600px; margin-top: 30px; margin-bottom: 50px;">
    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #fff;">
        
        <h2 style="margin-top: 0;">Edit Review Anda</h2>
        <hr>

        <?php if(isset($error)): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                <strong>Error:</strong> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <p style="margin: 5px 0;"><strong>Tipe Kamar:</strong> <?php echo $data_review['tipe_kamar']; ?></p>
        </div>

        <form action="" method="post">
            
            <input type="hidden" name="id_review" value="<?php echo $data_review['id_review']; ?>">

            <div style="margin-bottom: 15px;">
                <label for="rating" style="display: block; margin-bottom: 5px; font-weight: bold;">Rating:</label>
                <select name="rating" id="rating" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="5" <?php echo ($data_review['rating'] == 5) ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ Sangat Baik</option>
                    <option value="4" <?php echo ($data_review['rating'] == 4) ? 'selected' : ''; ?>>⭐⭐⭐⭐ Baik</option>
                    <option value="3" <?php echo ($data_review['rating'] == 3) ? 'selected' : ''; ?>>⭐⭐⭐ Cukup</option>
                    <option value="2" <?php echo ($data_review['rating'] == 2) ? 'selected' : ''; ?>>⭐⭐ Kurang</option>
                    <option value="1" <?php echo ($data_review['rating'] == 1) ? 'selected' : ''; ?>>⭐ Buruk</option>
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label for="komentar" style="display: block; margin-bottom: 5px; font-weight: bold;">Komentar:</label>
                <textarea name="komentar" id="komentar" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; resize: vertical;"><?php echo htmlspecialchars($data_review['komentar']); ?></textarea>
            </div>

            <button type="submit" name="update_review" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px;">
                Simpan Perubahan
            </button>
        </form>
        
        <br>
        <a href="riwayat_review.php" style="text-decoration: none; color: #666; display: block; text-align: center;">&laquo; Kembali</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>