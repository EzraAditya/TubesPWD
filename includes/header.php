<?php
if (!isset($_SESSION)) { session_start(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Penginapan</title>

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- CSS HALAMAN -->
    <?php if (isset($pageStyles)) echo $pageStyles; ?>
</head>

<body>

<header class="header">
    <div class="header-inner">

        <!-- LOGO -->
        <div class="header-logo">
            <span class="logo-icon">
                <img src="../assets/image/logo.png" alt="Logo" class="img-logo-header">
            </span>
            <span class="logo-text">NginapKan</span>
        </div>

        <!-- MENU -->
       <nav class="header-nav">
            <a href="dashboard.php">Home</a>
            <a href="daftar_kamar.php">Daftar Kamar</a>

            <?php if (isset($_SESSION['id_user'])): ?>
                <a href="riwayat.php">Riwayat Transaksi</a>
                <a href="riwayat_review.php">Riwayat Ulasan</a>
                
                <a href="profil.php" class="logout" style="background-color: #2c7be5;">
                    Halo, <?php echo $_SESSION['nama']; ?>
                </a>

            <?php else: ?>
                <a href="login.php" class="logout" style="background-color: #28a745;">
                    Log In
                </a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="content">