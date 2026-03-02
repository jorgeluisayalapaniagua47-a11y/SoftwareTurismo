<?php
session_start();
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Función para obtener pagos completados
function obtenerPagosCompletados($conn, $id_usuario) {
    $sql = "
        SELECT 
            p.id_pago, p.monto, p.fecha_pago, p.metodo_pago, p.estado_pago,
            d.nombre AS destino, d.descripcion, d.imagen_blob,
            r.id_reserva
        FROM pagos p
        JOIN reservas r ON p.id_reserva = r.id_reserva
        JOIN reserva_servicio rs ON r.id_reserva = rs.id_reserva
        JOIN servicios s ON rs.id_servicio = s.id_servicio
        JOIN destinos d ON s.id_destino = d.id_destino
        WHERE r.id_usuario = ? AND p.estado_pago = 'completado'
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    if ($stmt->execute()) {
        return $stmt->get_result();
    } else {
        echo "Error en la consulta de pagos completados: " . $stmt->error;
        return null;
    }
}

// Función para obtener reservas sin pago (pendientes)
function obtenerReservasPendientesDePago($conn, $id_usuario) {
  $sql = "
      SELECT 
          r.id_reserva,
          d.nombre AS destino, 
          d.descripcion, 
          d.imagen_blob,
          r.total AS monto_total  -- Usar el campo 'total' en lugar de 'monto_total'
      FROM reservas r
      LEFT JOIN pagos p ON r.id_reserva = p.id_reserva
      JOIN reserva_servicio rs ON r.id_reserva = rs.id_reserva
      JOIN servicios s ON rs.id_servicio = s.id_servicio
      JOIN destinos d ON s.id_destino = d.id_destino
      WHERE r.id_usuario = ? AND (p.id_pago IS NULL OR p.estado_pago != 'completado')
  ";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $id_usuario);
  if ($stmt->execute()) {
      return $stmt->get_result();
  } else {
      echo "Error en la consulta de pagos pendientes: " . $stmt->error;
      return null;
  }
}

$pagosRealizados = obtenerPagosCompletados($conn, $id_usuario);
$pagosPendientes = obtenerReservasPendientesDePago($conn, $id_usuario);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Pagos y Boletos</title>
  <link rel="stylesheet" href="../css/pagos.css">
  
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

<main>
  <h1>Mis Pagos y Boletos</h1>

  <!-- Pagos Pendientes -->
  <section>
  <h2>Pagos Pendientes</h2>
  <?php if ($pagosPendientes && $pagosPendientes->num_rows > 0): ?>
    <div class="pagos-container">
      <?php while ($row = $pagosPendientes->fetch_assoc()): ?>
        <div class="boleto">
          <h3>Reserva N° <?php echo $row['id_reserva']; ?></h3>
          <p><strong>Destino:</strong> <?php echo htmlspecialchars($row['destino']); ?></p>
          <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
          <?php if ($row['imagen_blob']): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagen_blob']); ?>" alt="Imagen destino" width="200">
          <?php endif; ?>
          <hr>
          <p><strong>Estado:</strong> <span style="color:orange;">Pendiente de pago</span></p>
          <!-- Botón para abrir el modal -->
          <button class="btn-pagar" data-id="<?php echo $row['id_reserva']; ?>" data-monto="<?php echo isset($row['total']) ? $row['total'] : 0; ?>">Realizar Pago</button>

        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>No tienes pagos pendientes.</p>
  <?php endif; ?>
</section>

    <!-- Pagos Realizados -->
    <section>
    <h2>Pagos Realizados</h2>
    <?php if ($pagosRealizados && $pagosRealizados->num_rows > 0): ?>
      <div class="pagos-container">
        <?php while ($row = $pagosRealizados->fetch_assoc()): ?>
          <div class="boleto">
            <h3>Reserva N° <?php echo $row['id_reserva']; ?></h3>
            <p><strong>Destino:</strong> <?php echo htmlspecialchars($row['destino']); ?></p>
            <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
            <?php if ($row['imagen_blob']): ?>
              <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagen_blob']); ?>" alt="Imagen destino" width="200">
            <?php endif; ?>
            <hr>
            <p><strong>Monto:</strong> $<?php echo number_format($row['monto'], 2); ?></p>
            <p><strong>Fecha de pago:</strong> <?php echo $row['fecha_pago']; ?></p>
            <p><strong>Método:</strong> <?php echo htmlspecialchars($row['metodo_pago']); ?></p>
            <p><strong>Estado:</strong> <span style="color:green;">Completado</span></p>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>No tienes pagos realizados.</p>
    <?php endif; ?>
  </section>

  <!-- Modal para realizar pago -->
  <div id="modal-pago" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Realizar Pago</h2>
    <form action="../process/procesar_pago.php" method="POST">
      <input type="hidden" name="id_reserva" id="modal-id-reserva">
      <label for="metodo">Método de Pago:</label>
      <select name="metodo_pago" id="metodo" required>
        <option value="Tarjeta">Tarjeta de Crédito</option>
        <option value="Transferencia">Transferencia Bancaria</option>
        <option value="Efectivo">Efectivo</option>
      </select>
      <label for="monto">Monto a Pagar:</label>
      <input type="number" name="monto" id="modal-monto" min="1" step="0.01" readonly required>

      <button type="submit" class="btn-confirmar">Confirmar Pago</button>
    </form>
  </div>
</div>

</main>

<script>
  window.addEventListener('scroll', function() {
    var header = document.querySelector('.header');
    header.classList.toggle('scrolled', window.scrollY > 0);
  });

  document.querySelectorAll('.btn-pagar').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const idReserva = this.getAttribute('data-id');
        const metodoPago = document.getElementById('metodo').value; // Asegúrate de obtener el método de pago correctamente

        // Petición AJAX para obtener el monto real desde el backend
        fetch('procesar_pago.php', {
            method: 'POST',
            body: new URLSearchParams({
                'id_reserva': idReserva,
                'metodo_pago': metodoPago
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Verifica la respuesta en la consola
            if (data.success) {
                document.getElementById('modal-id-reserva').value = idReserva;
                document.getElementById('modal-monto').value = data.monto; // Asignamos el monto al campo readonly
                document.getElementById('modal-pago').style.display = 'block';  // Mostramos el modal
            } else {
                alert('Error al obtener el monto: ' + data.message);
            }
        })
        .catch(error => {
            alert('Hubo un error en la solicitud AJAX: ' + error);
        });
    });
});


// Cerrar el modal al hacer clic en la X
document.querySelector('.close').onclick = function() {
    document.getElementById('modal-pago').style.display = 'none';
};

// Cerrar el modal al hacer clic fuera del modal
window.onclick = function(event) {
    const modal = document.getElementById('modal-pago');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};



</script>

</body>
</html>
