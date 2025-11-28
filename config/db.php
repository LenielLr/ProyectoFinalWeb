<?php
$host = "sql101.infinityfree.com";
$dbname = "if0_40547757_dblibreria";
$user = "if0_40547757";
$pass = "Le8494580891";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
