<?php
require "../config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"] ?? "";
$password = $data["password"] ?? "";

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Email dan password wajib"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user["password"])) {
    http_response_code(401);
    echo json_encode(["error" => "Email atau password salah"]);
    exit;
}

$_SESSION["user_id"] = $user["id_user"];

echo json_encode([
    "message" => "Login berhasil",
    "id_user" => $user["id_user"],
    "nama" => $user["nama"]
]);
