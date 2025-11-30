<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "dbtubes_pwd";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die(json_encode(["error" => "Koneksi gagal: " . $e->getMessage()]));
}

header("Content-Type: application/json");
?>
