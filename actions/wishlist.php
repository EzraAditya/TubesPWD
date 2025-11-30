<?php
session_start();
include '../config/database.php';
$id_user = $_SESSION['id_user'];

// ADD
if (isset($_GET['add'])) {
    $id_kamar = $_GET['add'];
    $cek = mysqli_query($conn, "SELECT * FROM wishlist WHERE id_user='$id_user' AND id_kamar='$id_kamar'");
    if(mysqli_num_rows($cek) == 0){
        mysqli_query($conn, "INSERT INTO wishlist (id_user, id_kamar) VALUES ('$id_user', '$id_kamar')");
    }
    header("Location: ../dashboard.php");
}

// DELETE
if (isset($_GET['delete'])) {
    $id_wish = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM wishlist WHERE id_wishlist='$id_wish'");
    header("Location: ../dashboard.php");
}
?>