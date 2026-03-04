<?php
session_start();
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = intval($_SESSION['id_usuario']);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// CONSULTA: LEFT JOIN para mostrar reservas aunque no tengan servicio asignado
$sql = "
    SELECT r.*, 
           COALESCE(s.nombre, 'Sin servicio') AS nombre_servicio,
           COALESCE(d.nombre, 'Sin destino') AS nombre_destino,
           COALESCE(s.descripcion, 'No disponible') AS descripcion_servicio
    FROM reservas r
    LEFT JOIN servicios s ON r.id_servicio = s.id_servicio
    LEFT JOIN destinos d ON s.id_destino = d.id_destino
    WHERE r.id_usuario = ?
    ORDER BY r.fecha_reserva DESC
    LIMIT 10
";



$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die('Error en la consulta: ' . $conn->error);
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$reservas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis Reservas - Agencia de Viajes</title>
  <link rel="stylesheet" href="../css/mis_reservas.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<header class="header">
    <div class="container">
        <div class="btn-menu">
            <label for="btn-menu">☰</label>
        </div>
        <nav class="menu">
            <a href="operador.php">Inicio</a>
            <a href="o_paises.php">Países</a>
            <a href="o_modificar MVC.php">Destinos</a>
            <a href="o_blog.php">Sugerencias</a>
        </nav>
    </div>
</header>


<div class="capa"></div>
<input type="checkbox" id="btn-menu" />
<div class="container-menu">
    <div class="cont-menu">
        <nav>
            <ul>
                <li><a href="o_mi-cuenta.php">Mi Cuenta</a></li>
                <li><a href="o_mis_reservas.php">Mis Reservas</a></li>
                <li><a href="o_pagos.php">Pagos y Boletos</a></li>
                <li><a href="o_politica.php">Politicas</a></li>
            </ul>
            <div class="logout-container">
             <a href="../auth/logout.php" id="logout-button" class="btn-logout">Cerrar Sesión</a>
            </div>

        </nav>
        <label for="btn-menu">✖️</label>
    </div>
</div>

<div class="table-container">
  <h2 class="mb-4">Mis Reservas</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Servicio</th>
        <th>Destino</th>
        <th>Descripción</th>
        <th>Fecha</th>
        <th>Personas</th>
        <th>Total</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
    <?php if ($reservas->num_rows > 0): ?>
        <?php while($row = $reservas->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre_servicio']) ?></td>
                <td><?= htmlspecialchars($row['nombre_destino']) ?></td>
                <td><?= htmlspecialchars($row['descripcion_servicio']) ?></td>
                <td><?= htmlspecialchars($row['fecha_reserva']) ?></td>
                <td><?= htmlspecialchars($row['cantidad_personas']) ?></td>
                <td>$<?= htmlspecialchars($row['total']) ?></td>
                <td><?= htmlspecialchars($row['estado']) ?></td>
                <td>
                <?php if ($row['estado'] == 'pendiente'): ?>
    <form action="../process/cancelar_reserva.php" method="POST">
        <input type="hidden" name="id_reserva" value="<?= $row['id_reserva'] ?>">
        <button type="submit" class="btn btn-danger">Cancelar Reserva</button>
    </form>
<?php endif; ?>

                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No tienes reservas registradas.</td>
        </tr>
    <?php endif; ?>
</tbody>


  </table>
</div>

<footer>
  <div class="footer-container">
    <div class="footer-section">
      <h2>Contáctanos</h2>
      <p>WhatsApp: +591 60933137</p>
      <p>Correo: agenciasdeviajes@gmail.com</p>
      <p>Agenda tu cita para planificar tu viaje soñado</p>
      <p class="certification">
        Rápido y al instante <span>Bolivia Corp.</span>
      </p>
    </div>

    <div class="footer-section">
      <h2>Redes Sociales</h2>
      <p>Facebook: Agencia de Viajes Bolivia Corp</p>
      <p>Tik Tok: Bolivia_Corp</p>
      <p>Instagram: Agencia_BoliviaCorp</p>
    </div>
  </div>

  <div class="footer-bottom">
    <p>&copy; 2025 Agencia de Viajes. Todos los derechos reservados.</p>
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

<?php
// Cerrar la conexión
$stmt->close();
$conn->close();
?>
