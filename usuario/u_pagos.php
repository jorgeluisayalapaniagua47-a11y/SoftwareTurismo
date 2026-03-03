<?php
session_start();
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/login.php");
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
            <a href="usuarios.php">Inicio</a>
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
          <!-- Botón para abrir el modal con más datos -->
          <button class="btn-pagar" 
                  data-id="<?php echo $row['id_reserva']; ?>" 
                  data-monto="<?php echo number_format($row['monto_total'], 2, '.', ''); ?>"
                  data-destino="<?php echo htmlspecialchars($row['destino']); ?>"
                  data-descripcion="<?php echo htmlspecialchars($row['descripcion']); ?>">
            Realizar Pago
          </button>

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
    <div class="modal-header-custom">
      <h2>Finalizar Compra</h2>
      <div class="header-line"></div>
    </div>
    
    <!-- Resumen de la Reserva -->
    <div class="resumen-reserva">
      <div class="resumen-item">
        <span class="resumen-label">Destino:</span>
        <span id="modal-destino-text" class="resumen-value"></span>
      </div>
      <div class="resumen-item">
        <span class="resumen-label">Descripción:</span>
        <p id="modal-descripcion-text" class="resumen-description"></p>
      </div>
    </div>

    <form action="../process/procesar_pago.php" method="POST">
      <input type="hidden" name="id_reserva" id="modal-id-reserva">
      
      <div class="form-group-custom">
        <label for="metodo">Método de Pago:</label>
        <select name="metodo_pago" id="metodo" required>
          <option value="Tarjeta">💳 Tarjeta de Crédito / Débito</option>
          <option value="Transferencia">🏦 Transferencia Bancaria</option>
          <option value="Efectivo">💵 Efectivo / Pago en Agencia</option>
        </select>
      </div>

      <!-- Campos de Tarjeta (Se muestran solo si selecciona Tarjeta) -->
      <div id="campos-tarjeta" class="campos-adicionales">
        <div class="form-group-custom">
          <label for="nombre_tarjeta">Nombre en la Tarjeta:</label>
          <input type="text" name="nombre_tarjeta" id="nombre_tarjeta" placeholder="Juan Pérez" required>
        </div>
        <div class="form-group-custom">
          <label for="numero_tarjeta">Número de Tarjeta:</label>
          <input type="text" name="numero_tarjeta" id="numero_tarjeta" placeholder="0000 0000 0000 0000" maxlength="19" required>
        </div>
        <div class="form-row-custom">
          <div class="form-group-custom">
            <label for="expiracion">Vencimiento:</label>
            <input type="text" name="expiracion" id="expiracion" placeholder="MM/AA" maxlength="5" required>
          </div>
          <div class="form-group-custom">
            <label for="cvv">CVV:</label>
            <input type="password" name="cvv" id="cvv" placeholder="123" maxlength="4" required>
          </div>
        </div>
      </div>

      <div class="form-group-custom monto-destacado">
        <label for="modal-monto">Total a Pagar:</label>
        <div class="monto-container">
          <span class="moneda">$</span>
          <input type="number" name="monto" id="modal-monto" min="1" step="0.01" readonly required>
        </div>
      </div>

      <div class="modal-form-actions">
        <button type="button" class="btn-cancelar close-modal-btn">Cancelar</button>
        <button type="submit" class="btn-confirmar-premium">Confirmar y Pagar Ahora</button>
      </div>
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
        const monto = this.getAttribute('data-monto');
        const destino = this.getAttribute('data-destino');
        const descripcion = this.getAttribute('data-descripcion');

        document.getElementById('modal-id-reserva').value = idReserva;
        document.getElementById('modal-monto').value = monto;
        document.getElementById('modal-destino-text').textContent = destino;
        document.getElementById('modal-descripcion-text').textContent = descripcion;
        
        document.getElementById('modal-pago').style.display = 'block';
    });
  });

  // Manejar el envío del formulario del modal mediante AJAX
  document.querySelector('#modal-pago form').addEventListener('submit', function(e) {
      if (!this.checkValidity()) {
          return; // Deja que el navegador muestre los mensajes de error nativos
      }
      e.preventDefault();
      
      const formData = new FormData(this);
      
      fetch('../process/procesar_pago.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert(data.message);
              location.reload(); // Recargar la página para ver los cambios
          } else {
              alert('Error: ' + data.message);
          }
      })
      .catch(error => {
          console.error('Error AJAX:', error);
          alert('Hubo un error al procesar el pago. Consulta el log para más detalles.');
      });
  });

  // Mostrar/Ocultar campos de tarjeta según el método seleccionado
  const selectMetodo = document.getElementById('metodo');
  const camposTarjeta = document.getElementById('campos-tarjeta');
  
  selectMetodo.addEventListener('change', function() {
      if (this.value === 'Tarjeta') {
          camposTarjeta.style.display = 'block';
      } else {
          camposTarjeta.style.display = 'none';
      }
  });

  // Cerrar el modal al hacer clic en la X o en el botón Cancelar
  document.querySelectorAll('.close, .close-modal-btn').forEach(closeBtn => {
      closeBtn.addEventListener('click', function() {
          document.getElementById('modal-pago').style.display = 'none';
      });
  });

  // Cerrar el modal al hacer clic fuera del modal
  window.addEventListener('click', function(event) {
      const modal = document.getElementById('modal-pago');
      if (event.target === modal) {
          modal.style.display = 'none';
      }
  });



</script>

</body>
</html>
