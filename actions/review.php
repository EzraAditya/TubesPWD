<?php
session_start();
include 'connection.php';

// Pastikan user login
if (!isset($_SESSION['id_user'])) { 
    echo "Login required";
    exit; 
}

// Ambil action dari URL
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id_user = $_SESSION['id_user']; 

// ================= 1. TAMBAH REVIEW =================
if ($action == 'tambah') {
    $id_kamar = $_POST['id_kamar'];
    $rating   = $_POST['rating'];
    $komentar = mysqli_real_escape_string($conn, $_POST['komentar']);

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
    $id_kamar = $_GET['id_kamar']; 

    $query = "SELECT r.*, u.nama 
              FROM review r 
              JOIN user u ON r.id_user = u.id 
              WHERE r.id_kamar = '$id_kamar'
              ORDER BY r.id_review DESC"; 

    $result = mysqli_query($conn, $query);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}

// ================= 3. EDIT REVIEW =================
elseif ($action == 'edit') {
    $id_review = $_POST['id_review'];
    $rating    = $_POST['rating'];
    $komentar  = mysqli_real_escape_string($conn, $_POST['komentar']);
    $query = "UPDATE review 
              SET rating='$rating', komentar='$komentar' 
              WHERE id_review='$id_review' AND id_user='$id_user'"; 

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