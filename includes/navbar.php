<?php if(isset($_SESSION['id_user'])) { ?>
    <div class="container.nav">
        <span>Halo, <strong><?php echo $_SESSION['nama']; ?></strong></span> |
        <a href="dashboard.php">Home</a> |
        <a href="profil.php">Profil</a> |
        <a href="riwayat.php">Riwayat</a> |
        <a href="actions/auth.php?logout=true" onclick="return confirm('Logout?')">Logout</a>
    </div>
<?php } ?>