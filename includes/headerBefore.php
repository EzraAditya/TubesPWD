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
            <a href="dashboardBefore.php">Home</a>
            <a href="login.php">Riwayat</a>
            <a href="login.php">Booking</a>
            <a href="login.php">Wishlist</a>
            <a href="login.php"><button>Log In</button></a>
        </nav>
    </div>
</header>

<main class="content"></main>