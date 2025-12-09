<?php
session_start();
include '../actions/connection.php';

if (!isset($_SESSION['id_user'])) { 
    header("Location: login.php"); 
    exit; 
}

$pageTitle = "Riwayat Ulasan";
include '../includes/header.php'; 

$id_user = $_SESSION['id_user'];

$query = "SELECT r.*, k.tipe_kamar 
          FROM review r 
          JOIN kamar k ON r.id_kamar = k.id_kamar 
          WHERE r.id_user = '$id_user' 
          ORDER BY r.id_review DESC";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">Riwayat Ulasan Anda</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Tipe Kamar</th>
                        <th width="15%">Rating</th>
                        <th>Komentar</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['tipe_kamar']); ?></strong>
                        </td>
                        <td style="color: #ffc107;">
                            <?php 
                            for($i = 0; $i < $row['rating']; $i++) {
                                echo '<i class="fas fa-star"></i> '; 
                            }
                            ?>
                            <div class="text-muted small text-dark">(<?php echo $row['rating']; ?>/5)</div>
                        </td>
                        <td><?php echo htmlspecialchars($row['komentar']); ?></td>
                        <td>
                            <a href="edit_review.php?id=<?php echo $row['id_review']; ?>" 
                               class="btn btn-sm btn-warning w-100 mb-1">Edit
                            </a>
                            <br>
                            <a href="../actions/review.php?action=hapus&id=<?php echo $row['id_review']; ?>" 
                               class="btn btn-sm btn-danger"
                               style="color:red"
                               onclick="return confirm('Yakin ingin menghapus ulasan ini?');">Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        
        <div class="alert alert-info text-center" role="alert">
            <h4>Belum ada ulasan</h4>
            <p>Anda belum pernah memberikan ulasan untuk kamar manapun.</p>
            <a href="riwayat.php" class="btn btn-primary">Lihat Riwayat Pemesanan</a>
        </div>

    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>