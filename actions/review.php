<?php
session_start();
include 'connection.php';

// Pastikan user login
if (!isset($_SESSION['id_user'])) { 
    echo "Login required";
    exit; 
}

// Ambil action dari URL, jika tidak ada set string kosong
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_user = $_SESSION['id_user']; // Ambil dari session sekali saja

// ================= 1. TAMBAH REVIEW =================
if ($action == 'tambah') {
    // Ambil data POST hanya DI DALAM blok ini
    $id_kamar = $_POST['id_kamar'];
    $rating   = $_POST['rating'];
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']); // Cegah error jika ada tanda kutip

    $query = "INSERT INTO review (id_user, id_kamar, rating, komentar) 
              VALUES ('$id_user', '$id_kamar', '$rating', '$komentar')";

    if (mysqli_query($conn, $query)) {
        echo "Review berhasil ditambahkan";
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }
}

// ================= 2. TAMPIL REVIEW =================
elseif ($action == 'tampil') {
    // Gunakan GET karena mengambil data
    $id_kamar = $_GET['id_kamar']; 

    // Query Join untuk mengambil nama user
    $query = "SELECT r.*, u.nama 
              FROM review r 
              JOIN user u ON r.id_user = u.id 
              WHERE r.id_kamar = '$id_kamar'
              ORDER BY r.id_review DESC"; // Urutkan dari yang terbaru

    $result = mysqli_query($conn, $query);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    // Set header JSON agar frontend membacanya dengan benar
    header('Content-Type: application/json');
    echo json_encode($data);
}

// ================= 3. EDIT REVIEW =================
elseif ($action == 'edit') {
    // Ambil data POST di sini
    $id_review = $_POST['id_review'];
    $rating    = $_POST['rating'];
    $komentar  = mysqli_real_escape_string($conn, $_POST['komentar']);

    // Pastikan WHERE menggunakan id_review (sesuai DB Anda)
    $query = "UPDATE review 
              SET rating='$rating', komentar='$komentar' 
              WHERE id_review='$id_review' AND id_user='$id_user'"; 
              // Tambahan AND id_user agar user tidak bisa edit punya orang lain

    if (mysqli_query($conn, $query)) {
        echo "Review berhasil diupdate";
    } else {
        echo "Gagal update: " . mysqli_error($conn);
    }
}

// ================= 4. HAPUS REVIEW =================
elseif ($action == 'hapus') {
    $id_review = $_GET['id'];

    $query = "DELETE FROM review WHERE id_review='$id_review' AND id_user='$id_user'";

    if (mysqli_query($conn, $query)) {
        echo "Review berhasil dihapus";
        header("Location: ../views/riwayat_review.php");
    } else {
        echo "Gagal hapus";
    }
}

// ================= 5. RATA-RATA RATING =================
elseif ($action == 'rating') {
    $id_kamar = $_GET['id_kamar'];

    $query = "SELECT AVG(rating) AS rata_rating FROM review WHERE id_kamar = '$id_kamar'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    // Jika belum ada rating, return 0, jika ada format 1 desimal
    echo $data['rata_rating'] ? number_format($data['rata_rating'], 1) : "0.0";
}
?>