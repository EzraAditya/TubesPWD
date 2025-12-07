<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['id_user'])) { header("Location: ../views/login.php"); exit; }
$id_user = $_SESSION['id_user'];
$id_kamar = $_POST['id_kamar'];
$rating = $_POST['rating'];
$komentar = $_POST['komentar'];

$action = isset($_GET['action']) ? $_GET['action'] : '';

// ================= TAMBAH REVIEW =================
if ($action == 'tambah') {

    $id_user = $_SESSION['id_user'];
    $id_kamar = $_POST['id_kamar'];
    $rating = $_POST['rating'];
    $komentar = $_POST['komentar'];

    $query = "INSERT INTO review
              (id_user, id_kamar, rating, komentar)
              VALUES
              ('$id_user', '$id_kamar', '$rating', '$komentar')";

    echo mysqli_query($conn, $query)
        ? "Review berhasil ditambahkan"
        : "Gagal menambahkan review";
}

// ================= TAMPIL REVIEW =================
elseif ($action == 'tampil') {

    $penginapan_id = $_GET['id_kamar'];

    $query = "SELECT r.*, u.nama
              FROM review r
              JOIN user u ON r.id_user = u.id
              WHERE r.id_kamar = '$id_kamar'";

    $result = mysqli_query($conn, $query);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
}

// ================= EDIT REVIEW =================
elseif ($action == 'edit') {

    $id_review = $_POST['id_review'];
    $rating    = $_POST['rating'];
    $komentar  = $_POST['komentar'];

    $query = "UPDATE review 
              SET rating='$rating', komentar='$komentar'
              WHERE id='$id_review'";

    echo mysqli_query($conn, $query)
        ? "Review berhasil diupdate"
        : "Gagal update review";
}

// ================= HAPUS REVIEW =================
elseif ($action == 'hapus') {

    $id_review = $_POST['id_review'];

    $query = "DELETE FROM review WHERE id='$id_review'";

    echo mysqli_query($conn, $query)
        ? "Review berhasil dihapus"
        : "Gagal menghapus review";
}

// ================= RATA-RATA RATING =================
elseif ($action == 'rating') {

    $id_kamar = $_GET['id_kamar'];

    $query = "SELECT AVG(rating) AS rata_rating 
              FROM review 
              WHERE id_kamar = '$id_kamar'";

    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    echo number_format($data['rata_rating'], 1);
}
?>