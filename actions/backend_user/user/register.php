<?php
require "../config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$nama = $data["nama"] ?? "";
$no_telp = $data["no_telp"] ?? "";
$email = $data["email"] ?? "";
$password = $data["password"] ?? "";
$tanggal_lahir = $data["tanggal_lahir"] ?? "";

if (!$nama || !$no_telp || !$email || !$password || !$tanggal_lahir) {
    http_response_code(400);
    echo json_encode(["error" => "Semua field wajib diisi"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id_user FROM user WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(["error" => "Email sudah terdaftar"]);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO user (nama, no_telp, email, password, tanggal_lahir) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$nama, $no_telp, $email, $hash, $tanggal_lahir]);

echo json_encode(["message" => "Registrasi berhasil"]);
