<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit; }
$id_user = $_SESSION['id_user'];

// BOOKING
if (isset($_POST['book_now'])) {
    $id_kamar = $_POST['id_kamar'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $tamu = $_POST['jumlah_tamu'];

    // Hitung Durasi
    $tgl1 = new DateTime($check_in);
    $tgl2 = new DateTime($check_out);
    $diff = $tgl2->diff($tgl1);
    $durasi = $diff->days;

    if ($durasi < 1) {
        echo "<script>alert('Minimal menginap 1 malam!'); window.history.back();</script>";
        exit;
    }

    // Ambil harga
    $kamar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga FROM kamar WHERE id_kamar='$id_kamar'"));
    $total = $kamar['harga'] * $durasi;

    // Insert Reservasi
    $sql1 = "INSERT INTO reservasi (id_user, check_in, check_out, jumlah_tamu, total_biaya, status_reservasi) 
             VALUES ('$id_user', '$check_in', '$check_out', '$tamu', '$total', 'Pending')";
    
    if (mysqli_query($conn, $sql1)) {
        $id_res = mysqli_insert_id($conn);
        // Insert Rincian
        $sql2 = "INSERT INTO rincianreservasi (id_reservasi, id_kamar, jumlah_kamar, sub_total) 
                 VALUES ('$id_res', '$id_kamar', 1, '$total')";
        mysqli_query($conn, $sql2);
        
        echo "<script>alert('Berhasil Booking!'); window.location='../riwayat.php';</script>";
    }
}

// CANCEL / DELETE
if (isset($_GET['action'])) {
    $id = $_GET['id'];
    if ($_GET['action'] == 'cancel') {
        mysqli_query($conn, "UPDATE reservasi SET status_reservasi='Dibatalkan' WHERE id_reservasi='$id'");
    } elseif ($_GET['action'] == 'delete') {
        mysqli_query($conn, "DELETE FROM reservasi WHERE id_reservasi='$id'");
    }
    header("Location: ../riwayat.php");
}
?>