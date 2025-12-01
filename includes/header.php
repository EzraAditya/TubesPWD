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
            <span class="logo-icon">🏨</span>
            <span class="logo-text">Penginapan</span>
        </div>

        <!-- MENU -->
        <nav class="header-nav">
            <a href="dashboard.php">Home</a>
            <a href="profil.php">Profil</a>
            <a href="riwayat.php">Riwayat</a>
            <a href="logout.php" class="logout">Logout</a>
        </nav>

        <!-- USER -->
        <div class="header-user">
            Halo, <strong><?php echo $_SESSION['nama']; ?></strong>
        </div>

    </div>
</header>

<main class="content">