<?php
session_start();
include '../actions/connection.php';

$pageStyles = '
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
';

include '../includes/header.php';
?>

<div class="container" style="margin-top: 20px;">

    <div style="text-align: center;">
        <span id="teksSapaan" class="sapaan">Halo...</span>
    </div>

    <div class="pembungkus-galeri">
        <button class="tombol-geser kiri" onclick="geserKiri()">&#10094;</button>
        
        <div class="galeri-sederhana" id="kotakGambar">
            <img src="../assets/image/home_1.png" alt="Hotel 1">
            <img src="../assets/image/home_2.png" alt="Hotel 2">
            <img src="../assets/image/home_3.png" alt="Hotel 3">
            <img src="../assets/image/home_4.png" alt="Hotel 4"> 
            <img src="../assets/image/home_5.png" alt="Hotel 5">
        </div>

        <button class="tombol-geser kanan" onclick="geserKanan()">&#10095;</button>
    </div>
    


    <hr>

    <div style="text-align: center; margin: 30px 0;">
        <h2>Selamat Datang di NginapKan</h2>
        <p>Penginapan murah, nyaman, dan aman. Pesan sekarang untuk liburan terbaik Anda.</p>
    </div>

    <h3>Daftar Kamar</h3>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <tr style="background-color: #eee;">
            <th>Tipe Kamar</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        
        <?php
        $q = mysqli_query($conn, "SELECT * FROM kamar LIMIT 5");
        while($k = mysqli_fetch_assoc($q)) {
        ?>
        <tr>
            <td>
                <b><?php echo $k['tipe_kamar']; ?></b><br>
                <small>Lantai: <?php echo $k['lantai']; ?></small>
            </td>
            <td style="color: blue;">
                Rp <?php echo number_format($k['harga']); ?>
            </td>
            <td>
                <?php if(isset($_SESSION['id_user'])) { ?>
                    <a href="booking.php?id=<?php echo $k['id_kamar']; ?>">Pesan</a> | 
                    <a href="../actions/wishlist.php?add=<?php echo $k['id_kamar']; ?>">Simpan</a>
                <?php } else { ?>
                    <a href="login.php" onclick="return alert('Eits, Login dulu ya kalau mau pesan!')">Pesan</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <br>
    
    <?php if (isset($_SESSION['id_user'])) { ?>
        <h3>Wishlist Saya</h3>
        <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
            <tr style="background-color: #ffe0e0;">
                <th>Kamar</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php
            $id = $_SESSION['id_user'];
            $qw = mysqli_query($conn, "SELECT w.id_wishlist, k.tipe_kamar, k.harga FROM wishlist w JOIN kamar k ON w.id_kamar = k.id_kamar WHERE w.id_user='$id'");
            
            while($w = mysqli_fetch_assoc($qw)) {
            ?>
            <tr>
                <td><?php echo $w['tipe_kamar']; ?></td>
                <td>Rp <?php echo number_format($w['harga']); ?></td>
                <td>
                    <a href="../actions/wishlist.php?delete=<?php echo $w['id_wishlist']; ?>" style="color: red;" onclick="return confirm('Yakin mau dihapus?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        <br><br>
    <?php } ?>

</div>

<script src="../assets/js/dashboard.js"></script>

<?php include '../includes/footer.php'; ?>