<?php
session_start();
require_once __DIR__ . "/config/db.php";

// Traer algunos libros destacados (los más recientes)
try {
    $sql = "SELECT id_titulo, titulo, tipo, precio 
            FROM titulos 
            ORDER BY fecha_pub DESC 
            LIMIT 5";
    $stmt = $pdo->query($sql);
    $destacados = $stmt->fetchAll();
} catch (PDOException $e) {
    $destacados = [];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Librería Online - Inicio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Librería Online</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="libros.php">Libros</a></li>
        <li class="nav-item"><a class="nav-link" href="autores.php">Autores</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
        <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

  <!-- Carrusel de libros destacados -->
  <div class="row">
    <div class="col-12">
      <h1 class="mb-3">Librería Online</h1>
      <p class="lead">Explora nuestros libros destacados y arma tu carrito de compras.</p>
    </div>
  </div>

  <?php if (!empty($destacados)): ?>
    <div id="carouselLibros" class="carousel slide mb-5" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <?php foreach ($destacados as $index => $libro): ?>
          <button type="button"
                  data-bs-target="#carouselLibros"
                  data-bs-slide-to="<?= $index ?>"
                  class="<?= $index === 0 ? 'active' : '' ?>"
                  aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                  aria-label="Slide <?= $index + 1 ?>"></button>
        <?php endforeach; ?>
      </div>

      <div class="carousel-inner rounded-3 shadow">
        <?php foreach ($destacados as $index => $libro): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> bg-dark text-light">
            <div class="d-flex flex-column flex-md-row align-items-center p-4 p-md-5"
                 style="min-height: 260px;">
              <div class="flex-grow-1">
                <h2 class="fw-bold">
                  <?= htmlspecialchars($libro['titulo']) ?>
                </h2>
                <p class="mb-1">
                  <span class="badge bg-primary me-2">
                    <?= htmlspecialchars($libro['tipo']) ?>
                  </span>
                  <?php if ($libro['precio'] !== null): ?>
                    <span class="fw-semibold">
                      Precio: <?= number_format($libro['precio'], 2) ?> USD
                    </span>
                  <?php else: ?>
                    <span class="fw-semibold text-warning">
                      Precio no disponible
                    </span>
                  <?php endif; ?>
                </p>
                <p class="mb-3">
                  Código: <code><?= htmlspecialchars($libro['id_titulo']) ?></code>
                </p>

                <?php if ($libro['precio'] !== null): ?>
                  <a href="carrito.php?accion=agregar&id_titulo=<?= urlencode($libro['id_titulo']) ?>"
                     class="btn btn-light btn-sm me-2">
                    Agregar al carrito
                  </a>
                <?php endif; ?>


                <a href="libros.php" class="btn btn-outline-light btn-sm">
                  Ver todos los libros
                </a>
              </div>
              <div class="mt-4 mt-md-0 ms-md-4 text-center">
                <!-- “Portada” simulada -->
                <div class="bg-secondary book-placeholder d-inline-flex align-items-center justify-content-center rounded-3 px-4 py-5">
                  <span class="h5 mb-0 text-wrap text-center">
                    <?= htmlspecialchars($libro['titulo']) ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#carouselLibros" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselLibros" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
    </div>
  <?php else: ?>
    <div class="alert alert-info">No hay libros destacados por el momento.</div>
  <?php endif; ?>

  <!-- Sección de “categorías” / info rápida -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Explora nuestro catálogo</h5>
          <p class="card-text">
            Consulta todos los libros disponibles y agrega tus favoritos al carrito.
          </p>
          <a href="libros.php" class="btn btn-primary btn-sm">Ver libros</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Conoce a los autores</h5>
          <p class="card-text">
            Descubre quién está detrás de cada obra en nuestra sección de autores.
          </p>
          <a href="autores.php" class="btn btn-primary btn-sm">Ver autores</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">¿Dudas o sugerencias?</h5>
          <p class="card-text">
            Escríbenos a través del formulario de contacto y guarda el mensaje en la base de datos.
          </p>
          <a href="contacto.php" class="btn btn-primary btn-sm">Ir a contacto</a>
        </div>
      </div>
    </div>
  </div>

</div><!-- /.container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
