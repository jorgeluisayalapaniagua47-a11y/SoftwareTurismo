<?php
session_start(); // Inicia la sesión

require_once '../config/conexion.php'; // Conexión a la base de datos

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id_usuario'])) {
        echo "<script>alert('Debes iniciar sesión para enviar un mensaje.'); window.location.href = 'login.php';</script>";
        exit();
    } else {
        $id_usuario = $_SESSION['id_usuario'];
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $message = $conn->real_escape_string($_POST['message']);

        // Insertar en la base de datos (solo guardamos el mensaje y el id del usuario)
        $sql = "INSERT INTO sugerencias (id_usuario, texto, fecha) VALUES (?, ?, NOW())"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $id_usuario, $message);

        if ($stmt->execute()) {
            echo "<script>alert('Mensaje enviado con éxito.'); window.location.href = 'blog.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacto - Agencia de Viajes</title>
  <link rel="stylesheet" type="text/css" href="../css/blog.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="btn-menu">
            <label for="btn-menu">☰</label>
        </div>
        <nav class="menu">
            <a href="administrador.php">Inicio</a>
            <a href="paises.php">Países</a>
            <a href="modificar MVC.php">Destinos</a>
            <a href="blog.php">Sugerencias</a>
        </nav>
    </div>
</header>


<div class="capa"></div>
<input type="checkbox" id="btn-menu" />
<div class="container-menu">
    <div class="cont-menu">
        <nav>
            <ul>
                <li><a href="mi-cuenta.php">Mi Cuenta</a></li>
                <li><a href="mis_reservas.php">Mis Reservas</a></li>
                <li><a href="pagos.php">Pagos y Boletos</a></li>
                <li><a href="bitacora.php">Bitacora</a></li>
                <li><a href="reporte.php">Reportes</a></li>
                <li><a href="politica.php">Politicas</a></li>
            </ul>
            <div class="logout-container">
             <a href="../auth/logout.php" id="logout-button" class="btn-logout">Cerrar Sesión</a>
            </div>

        </nav>
        <label for="btn-menu">✖️</label>
    </div>
</div>

<main>
  <section class="contact">
    <h2>Sugerencias</h2>
    <p>¿Tienes alguna pregunta o quieres más información? Contáctanos a través del siguiente formulario.</p>
    <div class="contact-form">
      <form action="" method="post">
        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Mensaje</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit">Enviar</button>
      </form>
    </div>
  </section>
</main>

<footer>
  <div class="footer-container">
    <div class="footer-section">
      <h2>Nuestros contactos</h2>
      <p>WhatsApp: +591 60933137</p>
      <p>Correo: agenciasdeviajes@gmail.com</p>
      <p>Programe una reunión y cotización con nosotros</p>
      <p class="certification">
        Rapido y al instante <span>Bolivia Corp.</span>
      </p>
    </div>

    <div class="footer-section">
      <h2>Redes sociales</h2>
      <p>Facebook: Agencia de Viajes Bolivia Corp</p>
      <p>Tik Tok: Bolivia_Corp</p>
      <p>Instagram: Agencia_BoliviaCorp</p>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; 2024 Agencia de Viajes. Todos los derechos reservados.</p>
  </div>
</footer>

<script>
  window.addEventListener('scroll', function() {
    var header = document.querySelector('.header');
    header.classList.toggle('scrolled', window.scrollY > 0);
  });
</script>
</body>
</html>
