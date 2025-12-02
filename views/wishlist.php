<?php
include '../actions/connection.php';
if (!isset($_SESSION['id_user'])) { header("Location: login.php"); exit; }

$pageTitle = "Riwayat";
$pageStyles = '<link rel="stylesheet" href="../assets/css/riwayat.css">';

include '../includes/header.php';

?>

<div class="container">
    <h2>WishList</h2>
    
</div>
<?php include '../includes/footer.php'; ?>