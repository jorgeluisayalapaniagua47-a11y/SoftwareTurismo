<?php
session_start(); // Iniciar la sesión
include '../config/conexion.php'; // Conexión a la base de datos

// Inicializar la variable mensaje
$mensaje = "";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Si no está autenticado, redirigir a la página de login
    header("Location: login.php");
    exit(); // Detener la ejecución si el usuario no está autenticado
}

// Obtener el id del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];

// Obtener los datos actuales del usuario
$sql_select = "SELECT nombre, correo, telefono, direccion FROM usuarios WHERE id_usuario = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $id_usuario);
$stmt_select->execute();
$resultado = $stmt_select->get_result();
$usuario = $resultado->fetch_assoc();

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $nueva_contraseña = $_POST['nueva_contraseña'];

    // Verificar si se proporcionó una nueva contraseña
    if (!empty($nueva_contraseña)) {
        // Cifrar nueva contraseña
        $contraseña_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        // Actualizar la base de datos con la nueva contraseña
        $sql_update = "UPDATE usuarios SET nombre=?, correo=?, telefono=?, direccion=?, contraseña=? WHERE id_usuario=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("sssssi", $nombre, $correo, $telefono, $direccion, $contraseña_hash, $id_usuario);
    } else {
        // Si no se proporciona nueva contraseña, solo actualizar los demás datos
        $sql_update = "UPDATE usuarios SET nombre=?, correo=?, telefono=?, direccion=? WHERE id_usuario=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssssi", $nombre, $correo, $telefono, $direccion, $id_usuario);
    }

    // Ejecutar la consulta de actualización
    if ($stmt->execute()) {
        $mensaje = "Perfil actualizado correctamente.";
        // Obtener los datos actualizados del usuario
        $stmt_select->execute();
        $resultado = $stmt_select->get_result();
        $usuario = $resultado->fetch_assoc();
    } else {
        $mensaje = "Error al actualizar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Modificar Perfil</title>
  <link rel="stylesheet" href="../css/mi-cuenta.css">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="btn-menu">
            <label for="btn-menu">☰</label>
        </div>
        <nav class="menu">
            <a href="u_destinos.php">Inicio</a>
            <a href="u_paises.php">Países</a>
            <a href="u_destinos.php">Destinos</a>
            <a href="u_blog.php">Sugerencias</a>
        </nav>
    </div>
</header>


<div class="capa"></div>
<input type="checkbox" id="btn-menu" />
<div class="container-menu">
    <div class="cont-menu">
        <nav>
            <ul>
                <li><a href="u_mi-cuenta.php">Mi Cuenta</a></li>
                <li><a href="u_mis_reservas.php">Mis Reservas</a></li>
                <li><a href="u_pagos.php">Pagos y Boletos</a></li>
                <li><a href="u_politica.php">Politicas</a></li>
            </ul>
            <div class="logout-container">
             <a href="../auth/logout.php" id="logout-button" class="btn-logout">Cerrar Sesión</a>
            </div>

        </nav>
        <label for="btn-menu">✖️</label>
    </div>
</div>

<main>
    <h1>Modificar Perfil</h1>
    <?php if ($mensaje): ?>
      <p class="mensaje"><?= $mensaje ?></p>
    <?php endif; ?>

    <form class="form-container" method="POST">
      <div class="form-group">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" id="nombre" name="nombre" class="form-input" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
      </div>

      <div class="form-group">
        <label for="correo" class="form-label">Correo</label>
        <input type="email" id="correo" name="correo" class="form-input" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
      </div>

      <div class="form-group">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="tel" id="telefono" name="telefono" class="form-input" value="<?= htmlspecialchars($usuario['telefono']) ?>">
      </div>

      <div class="form-group">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" id="direccion" name="direccion" class="form-input" value="<?= htmlspecialchars($usuario['direccion']) ?>">
      </div>

      <div class="form-group">
        <label for="nueva_contraseña" class="form-label">Nueva contraseña</label>
        <input type="password" id="nueva_contraseña" name="nueva_contraseña" class="form-input">
      </div>

      <div class="buttons">
        <button type="reset" class="btn-cancel">Cancelar</button>
        <button type="submit" class="btn-save">Guardar Cambios</button>
      </div>
    </form>
</main>

<script>      
    window.addEventListener('scroll', function() {
        var header = document.querySelector('.header');
        header.classList.toggle('scrolled', window.scrollY > 0);
    });
</script>
</body>
</html>
