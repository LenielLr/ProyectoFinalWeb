<?php
session_start();
require_once __DIR__ . "/config/db.php";

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$carrito = &$_SESSION['carrito'];

// Leer acción e id tanto desde GET como desde POST
$accion = $_POST["accion"] ?? $_GET["accion"] ?? "";
$id     = $_POST["id_titulo"] ?? $_GET["id_titulo"] ?? "";

// === AGREGAR (desde libros.php o desde el carrusel) ===
if ($accion === "agregar" && $id !== "") {

    $stmt = $pdo->prepare("SELECT id_titulo, titulo, precio FROM titulos WHERE id_titulo = :id");
    $stmt->execute([":id" => $id]);
    $libro = $stmt->fetch();

    if ($libro && $libro["precio"] !== null) {
        if (!isset($carrito[$id])) {
            $carrito[$id] = [
                "titulo"   => $libro["titulo"],
                "precio"   => $libro["precio"],
                "cantidad" => 1,
            ];
        } else {
            $carrito[$id]["cantidad"]++;
        }
    }

    header("Location: carrito.php");
    exit;
}

// === SUMAR (botón + en el carrito) ===
if ($accion === "sumar" && $id !== "") {
    if (isset($carrito[$id])) {
        $carrito[$id]["cantidad"]++;
    }
    header("Location: carrito.php");
    exit;
}

// === RESTAR (botón – en el carrito) ===
if ($accion === "restar" && $id !== "") {
    if (isset($carrito[$id])) {
        $carrito[$id]["cantidad"]--;
        // Si la cantidad llega a 0 o menos, se elimina del carrito
        if ($carrito[$id]["cantidad"] <= 0) {
            unset($carrito[$id]);
        }
    }
    header("Location: carrito.php");
    exit;
}

// === ELIMINAR (botón Eliminar) ===
if ($accion === "eliminar" && $id !== "") {
    if (isset($carrito[$id])) {
        unset($carrito[$id]);
    }
    header("Location: carrito.php");
    exit;
}

// === VACIAR TODO ===
if ($accion === "vaciar") {
    $carrito = [];
    header("Location: carrito.php");
    exit;
}

// Calcular total
$total = 0;
foreach ($carrito as $item) {
    $total += $item["precio"] * $item["cantidad"];
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Librería Online - Carrito de compras</title>
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
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="libros.php">Libros</a></li>
        <li class="nav-item"><a class="nav-link" href="autores.php">Autores</a></li>
        <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
        <li class="nav-item"><a class="nav-link active" href="carrito.php">Carrito</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h1>Carrito de compras</h1>

  <?php if (!empty($carrito)): ?>
    <table class="table table-striped table-bordered mt-3">
      <thead class="table-dark">
        <tr>
          <th>Código</th>
          <th>Título</th>
          <th>Precio</th>
          <th style="width: 180px;">Cantidad</th>
          <th>Subtotal</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($carrito as $id_titulo => $item): ?>
          <tr>
            <td><?= htmlspecialchars($id_titulo) ?></td>
            <td><?= htmlspecialchars($item["titulo"]) ?></td>
            <td><?= number_format($item["precio"], 2) ?></td>

            <!-- Cantidad con botones + y - -->
            <td class="text-center">
              <div class="d-inline-flex align-items-center gap-2">
                <!-- Botón restar -->
                <form method="post" action="carrito.php" class="d-inline">
                  <input type="hidden" name="accion" value="restar">
                  <input type="hidden" name="id_titulo" value="<?= htmlspecialchars($id_titulo) ?>">
                  <button type="submit" class="btn btn-sm btn-outline-secondary">-</button>
                </form>

                <span class="fw-bold">
                  <?= (int)$item["cantidad"] ?>
                </span>

                <!-- Botón sumar -->
                <form method="post" action="carrito.php" class="d-inline">
                  <input type="hidden" name="accion" value="sumar">
                  <input type="hidden" name="id_titulo" value="<?= htmlspecialchars($id_titulo) ?>">
                  <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                </form>
              </div>
            </td>

            <td><?= number_format($item["precio"] * $item["cantidad"], 2) ?></td>

            <td>
              <form method="post" action="carrito.php" class="d-inline">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="id_titulo" value="<?= htmlspecialchars($id_titulo) ?>">
                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center mt-3">
      <h4>Total: <?= number_format($total, 2) ?> USD</h4>
      <div>
        <form method="post" action="carrito.php" class="d-inline" onsubmit="return confirmarVaciar();">
          <input type="hidden" name="accion" value="vaciar">
          <button type="submit" class="btn btn-outline-secondary">Vaciar carrito</button>
        </form>
        <button class="btn btn-success ms-2" onclick="simularCompra();">
          Finalizar compra
        </button>
      </div>
    </div>

  <?php else: ?>
    <div class="alert alert-info mt-3">
      Tu carrito está vacío. Ve al listado de <a href="libros.php">libros</a> para agregar productos.
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
