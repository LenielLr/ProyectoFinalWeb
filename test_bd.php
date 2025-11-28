<?php
require_once __DIR__ . "/config/db.php";

echo "<h1>Prueba de conexión</h1>";

try {
    $sql = "SELECT COUNT(*) AS total FROM titulos";
    $stmt = $pdo->query($sql);
    $r = $stmt->fetch();

    echo "Total de libros: " . $r["total"];

} catch (PDOException $e) {
    echo "Error en consulta: " . $e->getMessage();
}
