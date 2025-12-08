<?php
session_start();
include '../actions/connection.php';
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

// 1. Ambil data reservasi dan pastikan statusnya 'Pending'
$query = "SELECT r.*, k.tipe_kamar FROM reservasi r 
          JOIN rincianreservasi rr ON r.id_reservasi = rr.id_reservasi 
          JOIN kamar k ON rr.id_kamar = k.id_kamar 
          WHERE r.id_reservasi = '$id_reservasi' AND r.id_user = '$id_user' AND r.status_reservasi = 'Pending'";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<script>alert('Pesanan tidak dapat diproses (sudah dibayar/dibatalkan)!'); window.location='riwayat.php';</script>";
    exit;
}

// 2. (OTOMATIS) Update Status Reservasi menjadi 'Confirmed' (Tanpa Admin)
// Ini menganggap user telah melihat informasi pembayaran dan melakukan transfer.
$update_reservasi = "UPDATE reservasi SET status_reservasi = 'Confirmed' WHERE id_reservasi = '$id_reservasi'";
mysqli_query($conn, $update_reservasi);

$pageTitle = "Pembayaran";
include '../includes/header.php';
?>

<div class="container" style="max-width: 600px; margin-top: 30px;">
    <div style="border: 1px solid #c3e6cb; padding: 20px; border-radius: 8px; background: #d4edda; color: #155724;">
        <h4 style="margin-top: 0;">✅ Pembayaran Berhasil Dikonfirmasi!</h4>
        <p>Pesanan Anda **#<?php echo $data['id_reservasi']; ?>** telah **terkonfirmasi**. Silakan persiapkan diri untuk tanggal *check-in* Anda.</p>
    </div>

    <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #fff; margin-top: 20px;">
        <h2 style="margin-top: 0;">Detail Pembayaran</h2>
        <hr>
        
        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <p style="margin: 5px 0;"><strong>ID Pesanan:</strong> #<?php echo $data['id_reservasi']; ?></p>
            <p style="margin: 5px 0;"><strong>Total Tagihan:</strong> <span style="font-size: 18px; color: green; font-weight: bold;">Rp <?php echo number_format($data['total_biaya']); ?></span></p>
        </div>

        <h4>Informasi Rekening Tujuan:</h4>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: 10px; border: 1px solid #eee; padding: 10px; border-left: 4px solid blue;">
                **Bank BCA** | No. Rek: 123-456-7890 | A/N: Sistem Penginapan
            </li>
        </ul>
        
        <a href="riwayat.php" 
           style="display: block; background: #007bff; color: white; padding: 10px; border-radius: 5px; text-decoration: none; text-align: center; margin-top: 15px;">
           Lihat Riwayat Pesanan
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>