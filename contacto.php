<?php
require_once __DIR__ . "/config/db.php";

$mensaje = "";
$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre     = trim($_POST["nombre"] ?? "");
    $correo     = trim($_POST["correo"] ?? "");
    $asunto     = trim($_POST["asunto"] ?? "");
    $comentario = trim($_POST["comentario"] ?? "");

    if ($nombre === "")   $errores[] = "El nombre es obligatorio.";
    if ($correo === "")   $errores[] = "El correo es obligatorio.";
    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "El correo no es válido.";
    if ($asunto === "")   $errores[] = "El asunto es obligatorio.";
    if ($comentario === "") $errores[] = "El comentario es obligatorio.";

    if (empty($errores)) {
        try {
            $sql = "INSERT INTO contacto (fecha, correo, nombre, asunto, comentario)
                    VALUES (NOW(), :correo, :nombre, :asunto, :comentario)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":correo"     => $correo,
                ":nombre"     => $nombre,
                ":asunto"     => $asunto,
                ":comentario" => $comentario,
            ]);
            $mensaje = "Tu mensaje se ha enviado correctamente.";
            $_POST = []; // limpia el formulario
        } catch (PDOException $e) {
            $errores[] = "Error al guardar el mensaje: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Librería Online - Contacto</title>
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
        <li class="nav-item"><a class="nav-link" href="autores.php">Autores</a></li>
        <li class="nav-item"><a class="nav-link active" href="contacto.php">Contacto</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h1>Formulario de contacto</h1>

  <?php if ($mensaje): ?>
    <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
  <?php endif; ?>

  <?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errores as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="contacto.php" class="mt-3">
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre completo</label>
      <input type="text" name="nombre" id="nombre" class="form-control"
             value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="correo" class="form-label">Correo electrónico</label>
      <input type="email" name="correo" id="correo" class="form-control"
             value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="asunto" class="form-label">Asunto</label>
      <input type="text" name="asunto" id="asunto" class="form-control"
             value="<?= htmlspecialchars($_POST['asunto'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label for="comentario" class="form-label">Comentario</label>
      <textarea name="comentario" id="comentario" rows="4" class="form-control" required><?= htmlspecialchars($_POST['comentario'] ?? '') ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Enviar</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
