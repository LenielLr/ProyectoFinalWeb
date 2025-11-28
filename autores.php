<?php
require_once __DIR__ . "/config/db.php";

$sql = "SELECT id_autor, nombre, apellido, telefono, ciudad, estado, pais
        FROM autores";
$stmt = $pdo->query($sql);
$autores = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Librería Online - Autores</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Librería Online</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="libros.php">Libros</a></li>
        <li class="nav-item"><a class="nav-link active" href="autores.php">Autores</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h1>Listado de autores</h1>

  <?php if (count($autores) > 0): ?>
    <table class="table table-striped table-bordered mt-3">
      <thead class="table-dark">
        <tr>
          <th>ID Autor</th>
          <th>Nombre completo</th>
          <th>Teléfono</th>
          <th>Ciudad</th>
          <th>Estado</th>
          <th>País</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($autores as $autor): ?>
          <tr>
            <td><?= htmlspecialchars($autor['id_autor']) ?></td>
            <td><?= htmlspecialchars(trim($autor['nombre']) . " " . $autor['apellido']) ?></td>
            <td><?= htmlspecialchars($autor['telefono']) ?></td>
            <td><?= htmlspecialchars($autor['ciudad']) ?></td>
            <td><?= htmlspecialchars($autor['estado']) ?></td>
            <td><?= htmlspecialchars($autor['pais']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info mt-3">No hay autores registrados.</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
