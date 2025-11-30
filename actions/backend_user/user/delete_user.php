<?php
require "../config.php";

$data = json_decode(file_get_contents("php://input"), true);
$id_user = $data["id_user"];

$stmt = $pdo->prepare("DELETE FROM user WHERE id_user = ?");
$ok = $stmt->execute([$id_user]);

echo json_encode([
    "status" => $ok,
    "message" => $ok ? "User berhasil dihapus" : "Gagal menghapus user"
]);
