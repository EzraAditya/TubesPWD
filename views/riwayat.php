<?php
include '../actions/connection.php';
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }

$pageTitle = "Riwayat";
$pageStyles = '<link rel="stylesheet" href="../assets/css/riwayat.css">';

include '../includes/header.php';

?>

<div class="container">
    <h2>Riwayat Transaksi</h2>
    <table border="1">
        <thead>
            <tr><th>ID</th><th>Kamar</th><th>Tgl Check In/Out</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php
            $id = $_SESSION['id_user'];
            $q = "SELECT r.*, k.tipe_kamar FROM reservasi r 
                  JOIN rincianreservasi rr ON r.id_reservasi = rr.id_reservasi 
                  JOIN kamar k ON rr.id_kamar = k.id_kamar 
                  WHERE r.id_user='$id' ORDER BY r.id_reservasi DESC";
            $res = mysqli_query($conn, $q);
            
            while($r = mysqli_fetch_assoc($res)) {
            ?>
            <tr>
                <td>#<?php echo $r['id_reservasi']; ?></td>
                <td><?php echo $r['tipe_kamar']; ?></td>
                <td><?php echo $r['check_in']; ?> s/d <?php echo $r['check_out']; ?></td>
                <td>Rp <?php echo number_format($r['total_biaya']); ?></td>
                <td><?php 
                    echo $r['status_reservasi']; 
                    $class = $st == 'Pending' ? 'status status-pending' : ($st == 'Selesai' ? 'status status-done' : 'status status-cancel');
                    echo "<span class='$class'>$st</span>";
                ?></td>
                <td>
                    <?php if($r['status_reservasi'] == 'Pending') { ?>
                        <a href="../actions/reservation.php?action=cancel&amp;id=<?php echo $r['id_reservasi']; ?>" onclick="return confirm('Batalkan?')">Batalkan</a>
                    <?php } else { ?>
                        <a href="../actions/reservation.php?action=delete&amp;id=<?php echo $r['id_reservasi']; ?>" onclick="return confirm('Hapus?')" style="color:red;">Hapus</a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>