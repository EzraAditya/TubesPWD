<?php
require "../config.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_user       = $data['id_user'];
$nama          = $data['nama'];
$no_telp       = $data['no_telp'];
$tanggal_lahir = $data['tanggal_lahir'];

$stmt = $pdo->prepare("UPDATE user 
                       SET nama = ?, no_telp = ?, tanggal_lahir = ?
                       WHERE id_user = ?");
$ok = $stmt->execute([$nama, $no_telp, $tanggal_lahir, $id_user]);

echo json_encode([
    "status" => $ok,
    "message" => $ok ? "Profil berhasil diperbarui" : "Gagal update profil"
]);
