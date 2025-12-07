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

    $user_id       = $_POST['user_id'];
    $penginapan_id = $_POST['penginapan_id'];
    $rating        = $_POST['rating'];
    $komentar      = $_POST['komentar'];
    $tanggal       = date('Y-m-d H:i:s');

    $query = "INSERT INTO review
              (user_id, penginapan_id, rating, komentar, tanggal)
              VALUES
              ('$user_id', '$penginapan_id', '$rating', '$komentar', '$tanggal')";

    echo mysqli_query($conn, $query)
        ? "Review berhasil ditambahkan"
        : "Gagal menambahkan review";
}

// ================= TAMPIL REVIEW =================
elseif ($action == 'tampil') {

    $penginapan_id = $_GET['penginapan_id'];

    $query = "SELECT r.*, u.nama
              FROM review r
              JOIN user u ON r.user_id = u.id
              WHERE r.penginapan_id = '$penginapan_id'
              ORDER BY r.tanggal DESC";

    $result = mysqli_query($conn, $query);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode($data);
}

// ================= EDIT REVIEW =================
elseif ($action == 'edit') {

    $review_id = $_POST['review_id'];
    $rating    = $_POST['rating'];
    $komentar  = $_POST['komentar'];

    $query = "UPDATE review 
              SET rating='$rating', komentar='$komentar'
              WHERE id='$review_id'";

    echo mysqli_query($conn, $query)
        ? "Review berhasil diupdate"
        : "Gagal update review";
}

// ================= HAPUS REVIEW =================
elseif ($action == 'hapus') {

    $review_id = $_POST['review_id'];

    $query = "DELETE FROM review WHERE id='$review_id'";

    echo mysqli_query($conn, $query)
        ? "Review berhasil dihapus"
        : "Gagal menghapus review";
}

// ================= RATA-RATA RATING =================
elseif ($action == 'rating') {

    $penginapan_id = $_GET['penginapan_id'];

    $query = "SELECT AVG(rating) AS rata_rating 
              FROM review 
              WHERE penginapan_id = '$penginapan_id'";

    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    echo number_format($data['rata_rating'], 1);
}
?>