<?php
session_start();
include '../actions/connection.php';
if (!isset($_SESSION['id_user'])) { 
    header("Location: login.php"); 
    exit; 
}

$pageTitle = "Riwayat";
include '../includes/header.php';

$id = $_SESSION['id_user'];
$q = "SELECT r.*, k.tipe_kamar FROM reservasi r 
      JOIN rincianreservasi rr ON r.id_reservasi = rr.id_reservasi 
      JOIN kamar k ON rr.id_kamar = k.id_kamar 
      WHERE r.id_user='$id' ORDER BY r.id_reservasi DESC";
$res = mysqli_query($conn, $q);
?>

<div class="container">
    <h2>Riwayat Transaksi</h2>
    
    <?php if (mysqli_num_rows($res) > 0): ?>
    <table border="1" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="padding:10px;">ID</th>
                <th style="padding:10px;">Kamar</th>
                <th style="padding:10px;">Check In/Out</th>
                <th style="padding:10px;">Total</th>
                <th style="padding:10px;">Status</th>
                <th style="padding:10px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($r = mysqli_fetch_assoc($res)): 
                $status = $r['status_reservasi'];
                $status_color = '';
                
                // --- LOGIKA STATUS & WARNA ---
                if ($status == 'Pending') {
                    $status_color = 'orange';
                } elseif ($status == 'Confirmed') {
                    $status_color = 'blue';
                } elseif ($status == 'Selesai') {
                    $status_color = 'green';
                } elseif ($status == 'Canceled') {
                    $status_color = 'red';
                }
                
                // Logika untuk menentukan apakah transaksi sudah Selesai (cocok untuk review)
                $is_finished = ($status == 'Selesai');
                
            ?>
            <tr>
                <td style="padding:10px;">#<?php echo $r['id_reservasi']; ?></td>
                <td style="padding:10px;"><?php echo $r['tipe_kamar']; ?></td>
                <td style="padding:10px;">
                    <?php echo date('d/m/Y', strtotime($r['check_in'])); ?><br>
                    s/d<br>
                    <?php echo date('d/m/Y', strtotime($r['check_out'])); ?>
                </td>
                <td style="padding:10px;">Rp <?php echo number_format($r['total_biaya']); ?></td>
                <td style="padding:10px;">
                    <span style="color:<?php echo $status_color; ?>; font-weight:bold;">
                        <?php echo $status; ?>
                    </span>
                </td>
                <td style="padding:10px;">
                    <?php if($status == 'Pending'): ?>
                        <a href="edit_booking.php?id=<?php echo $r['id_reservasi']; ?>"
                            style="display:inline-block; background-color:#2c7be5; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; font-size:12px; margin-bottom:5px;">
                        Edit Reservasi</a>
                        <br>
                        <a href="pembayaran.php?id=<?php echo $r['id_reservasi']; ?>" 
                            style="display:inline-block; background-color:#28a745; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; font-size:12px; margin-bottom:5px;">
                            Bayar Sekarang
                        </a>
                        <br>
                        <a href="../actions/reservation.php?action=cancel&id=<?php echo $r['id_reservasi']; ?>" 
                            onclick="return confirm('Batalkan?')" 
                            style="color:red; text-decoration:none;">
                            Batalkan
                        </a>
                        
                    <?php elseif($is_finished): ?>
                        <a href="ulasan.php?id=<?php echo $r['id_reservasi']; ?>" 
                        style="display:inline-block; background-color:#007bff; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; font-size:12px; margin-bottom:5px;">
                        Beri Ulasan ⭐
                        </a>
                        <br>
                        <a href="../actions/reservation.php?action=delete&id=<?php echo $r['id_reservasi']; ?>" 
                            onclick="return confirm('Hapus?')" 
                            style="color:#666; text-decoration:none; font-size:12px;">
                            Hapus Riwayat
                        </a>

                    <?php elseif(in_array($status, ['Confirmed', 'Canceled'])): ?>
                        <a href="riview.php?id=<?php echo $r['id_reservasi']; ?>" 
                            style="display:inline-block; background-color:#28a745; color:white; padding:5px 10px; border-radius:4px; text-decoration:none; font-size:12px; margin-bottom:5px;">
                            Riview
                        </a>
                        
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="text-align:center; padding:20px; background:#f9f9f9;">
        Tidak ada riwayat transaksi.
        <br>
        <a href="daftar_kamar.php" style="color:blue;">Booking kamar sekarang</a>
    </p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>