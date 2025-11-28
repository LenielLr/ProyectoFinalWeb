<?php
require_once __DIR__ . "/config/db.php";

// Traer todos los libros con el nombre del publicador
$sql = "SELECT t.id_titulo, t.titulo, t.tipo, t.precio, p.nombre_pub
        FROM titulos t
        JOIN publicadores p ON t.id_pub = p.id_pub";
$stmt = $pdo->query($sql);
$libros = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Librería Online - Libros</title>
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
        <li class="nav-item"><a class="nav-link active" href="libros.php">Libros</a></li>
        <li class="nav-item"><a class="nav-link" href="autores.php">Autores</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h1>Listado de libros</h1>

  <?php if (count($libros) > 0): ?>
    <table class="table table-striped table-bordered mt-3">
      <thead class="table-dark">
        <tr>
          <th>Código</th>
          <th>Título</th>
          <th>Tipo</th>
          <th>Precio</th>
          <th>Editorial</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($libros as $libro): ?>
          <tr>
            <td><?= htmlspecialchars($libro['id_titulo']) ?></td>
            <td><?= htmlspecialchars($libro['titulo']) ?></td>
            <td><?= htmlspecialchars($libro['tipo']) ?></td>
            <td><?= $libro['precio'] !== null ? number_format($libro['precio'], 2) : 'N/D' ?></td>
            <td><?= htmlspecialchars($libro['nombre_pub']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info mt-3">No hay libros registrados.</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
