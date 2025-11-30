<?php
require "../config.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Harus login"]);
    exit;
}

$stmt = $pdo->query("SELECT id_user, nama, no_telp, email, tanggal_lahir FROM user ORDER BY id_user DESC");
$users = $stmt->fetchAll();

echo json_encode($users);
