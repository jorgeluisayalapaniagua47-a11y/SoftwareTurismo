<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/conexion.php';

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

echo "Bienvenido, " . htmlspecialchars($_SESSION['nombre']);

$categorias = $conn->query("SELECT nombre FROM categorias");

$filtro = "";
if (isset($_GET['categoria']) && $_GET['categoria'] != '') {
    $categoria = $conn->real_escape_string($_GET['categoria']);
    $filtro = "WHERE c.nombre = '$categoria'";
}

$sql = "
    SELECT s.*, 
           c.nombre AS nombre_categoria, 
           d.nombre AS nombre_destino, 
           d.descripcion AS descripcion_destino, 
           d.imagen_blob 
    FROM servicios s
    JOIN categorias c ON s.id_categoria = c.id_categoria
    JOIN destinos d ON s.id_destino = d.id_destino
    $filtro
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Servicios - Agencia de Viajes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/destinos.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      padding-top: 100px;
      left: 0; top: 0;
      width: 100%; height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 90%;
      max-width: 400px;
      border-radius: 10px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
  </style>
</head>
<body>
<header class="header">
    <div class="container">
        <div class="btn-menu">
            <label for="btn-menu">☰</label>
        </div>
        <nav class="menu">
            <a href="usuarios.php">Inicio</a>
            <a href="paises.php">Países</a>
            <a href="destinos.php">Destinos</a>
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
  <section class="destinations">
    <header><h2>Servicios Turísticos</h2></header>

    <form method="GET" class="filter-form">
      <label for="categoria">Filtrar por categoría:</label>
      <select name="categoria" id="categoria" onchange="this.form.submit()">
        <option value="">-- Todas las categorías --</option>
        <?php while ($cat = $categorias->fetch_assoc()): ?>
          <option value="<?php echo htmlspecialchars($cat['nombre']); ?>" 
            <?php if (isset($_GET['categoria']) && $_GET['categoria'] === $cat['nombre']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($cat['nombre']); ?>
          </option>
        <?php endwhile; ?>
      </select>
    </form>

    <div class="destination-grid">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <article class="destination-card">
            <div class="icon-container">
              <?php if ($row['imagen_blob']): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagen_blob']); ?>" alt="<?php echo htmlspecialchars($row['nombre_destino']); ?>" class="icon">
              <?php else: ?>
                <img src="img destinos/default.jpg" alt="Sin imagen" class="icon">
              <?php endif; ?>
            </div>
            <h3><?php echo htmlspecialchars($row['nombre']); ?></h3>
            <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
            <p><em>Destino: <?php echo htmlspecialchars($row['nombre_destino']); ?></em></p>
            <p><strong>Descripción del destino:</strong> <?php echo htmlspecialchars($row['descripcion_destino']); ?></p>
            <p><em>Categoría: <?php echo htmlspecialchars($row['nombre_categoria']); ?></em></p>
            <p><strong>Precio:</strong> $<?php echo htmlspecialchars($row['precio']); ?></p>
            <button type="button" class="open-modal" data-precio="<?php echo $row['precio']; ?>" data-idservicio="<?php echo $row['id_servicio']; ?>">Crear reserva</button>
          </article>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No se encontraron resultados.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

<!-- Modal -->
<div id="reservaModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Reservar Servicio</h2>
    <form id="reservaForm" novalidate>
      <div class="form-group">
        <label for="fecha_reserva">Fecha de Reserva</label>
        <input type="date" class="form-control" id="fecha_reserva" name="fecha_reserva" required>
      </div>
      <div class="form-group">
        <label for="cantidad_personas">Cantidad de Personas</label>
        <input type="number" class="form-control" id="cantidad_personas" name="cantidad_personas" required min="1">
      </div>
      <div class="form-group">
        <label for="total">Total</label>
        <input type="text" class="form-control" id="total" name="total" required readonly>
      </div>
      <input type="hidden" id="id_servicio" name="id_servicio">
      <button type="submit" class="btn btn-success btn-block">Confirmar Reserva</button>
    </form>
  </div>
</div>

<footer>
  <div class="footer-container">
    <div class="footer-section">
      <h2>Contáctanos</h2>
      <p>WhatsApp: +591 60933137</p>
      <p>Correo: agenciasdeviajes@gmail.com</p>
      <p>Agenda tu cita para planificar tu viaje soñado</p>
      <p class="certification">Rápido y al instante <span>Bolivia Corp.</span></p>
    </div>
    <div class="footer-section">
      <h2>Redes Sociales</h2>
      <p>Facebook: Agencia de Viajes Bolivia Corp</p>
      <p>TikTok: Bolivia_Corp</p>
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
document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById("reservaModal");
  const openButtons = document.querySelectorAll(".open-modal");
  const closeBtn = document.querySelector(".modal .close");

  // Asegurarse de que id_usuario es válido
  const id_usuario = <?php echo json_encode($_SESSION['id_usuario']); ?>;

  if (!id_usuario) {
    alert("El usuario no está autenticado o no se encontró el ID de usuario.");
    return;  // Detenemos el script si no hay un ID de usuario válido
  }

  openButtons.forEach(button => {
    button.onclick = function() {
      const precio = parseFloat(button.getAttribute("data-precio"));
      const id_servicio = button.getAttribute("data-idservicio");
      document.getElementById("id_servicio").value = id_servicio;

      const cantidadInput = document.getElementById("cantidad_personas");
      const totalInput = document.getElementById("total");

      cantidadInput.value = "";
      totalInput.value = "";

      cantidadInput.addEventListener('input', function() {
        const cantidad = parseInt(cantidadInput.value);
        if (cantidad > 0) {
          totalInput.value = (precio * cantidad).toFixed(2);
        } else {
          totalInput.value = "";
        }
      });

      modal.style.display = "block";
    };
  });

  closeBtn.onclick = function() {
    modal.style.display = "none";
  };

  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };

  const reservaForm = document.getElementById("reservaForm");
  reservaForm.onsubmit = function(event) {
    event.preventDefault();

    const fecha = document.getElementById("fecha_reserva").value;
    const cantidad = document.getElementById("cantidad_personas").value;
    const total = document.getElementById("total").value;
    const id_servicio = document.getElementById("id_servicio").value;

    fetch('../process/crear_reserva.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `fecha_reserva=${fecha}&cantidad_personas=${cantidad}&total=${total}&id_servicio=${id_servicio}&id_usuario=${id_usuario}`
    })
    .then(response => response.text())
    .then(data => {
      alert(data);
      modal.style.display = "none";
    })
    .catch(error => console.error('Error:', error));
  };
});

</script>

</body>
</html>
