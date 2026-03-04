<?php
$host = 'localhost';
$usuario = 'root';
$contraseña = '';
$base_de_datos = 'turismo';

$conn = new mysqli($host, $usuario, $contraseña, $base_de_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar creación y edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $departamento = $conn->real_escape_string($_POST['departamento']);
    $atractivos = $conn->real_escape_string($_POST['atractivos']);
    $id_categoria = intval($_POST['id_categoria']);
    $precio = floatval($_POST['precio']);
    $duracion = $conn->real_escape_string($_POST['duracion']);
    $id_operador = 1;

    $imagen_blob = null;
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $imagen_blob = file_get_contents($_FILES['imagen']['tmp_name']);
    }

    // Validar que no exista un destino con el mismo nombre (insensible a mayúsculas)
    $stmt_check = $conn->prepare("SELECT id_destino FROM destinos WHERE LOWER(nombre) = LOWER(?)");
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $existe = $stmt_check->get_result();

    if (isset($_POST['id_destino']) && $_POST['id_destino'] != '') {
        $id_destino = intval($_POST['id_destino']);
        $row_existe = $existe->fetch_assoc();
        if ($row_existe && (int)$row_existe['id_destino'] !== $id_destino) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?editar=" . $id_destino . "&error=destino_duplicado");
            exit;
        }
        if ($imagen_blob) {
            $stmt = $conn->prepare("UPDATE destinos SET nombre=?, departamento=?, descripcion=?, atractivos=?, imagen_blob=?, id_categoria=? WHERE id_destino=?");
            $stmt->bind_param("ssssssi", $nombre, $departamento, $descripcion, $atractivos, $imagen_blob, $id_categoria, $id_destino);
        } else {
            $stmt = $conn->prepare("UPDATE destinos SET nombre=?, departamento=?, descripcion=?, atractivos=?, id_categoria=? WHERE id_destino=?");
            $stmt->bind_param("ssssii", $nombre, $departamento, $descripcion, $atractivos, $id_categoria, $id_destino);
        }
        $stmt->execute();

        $res = $conn->query("SELECT * FROM servicios WHERE id_destino = $id_destino");
        if ($res->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE servicios SET nombre=?, descripcion=?, precio=?, duracion=?, id_categoria=?, id_operador=? WHERE id_destino=?");
            $stmt->bind_param("ssdsiii", $nombre, $descripcion, $precio, $duracion, $id_categoria, $id_operador, $id_destino);
        } else {
            $stmt = $conn->prepare("INSERT INTO servicios (nombre, descripcion, precio, duracion, id_categoria, id_destino, id_operador) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsiii", $nombre, $descripcion, $precio, $duracion, $id_categoria, $id_destino, $id_operador);
        }
        $stmt->execute();
    } else {
        if ($existe->num_rows > 0) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?error=destino_duplicado");
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO destinos (nombre, departamento, descripcion, atractivos, imagen_blob, id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $nombre, $departamento, $descripcion, $atractivos, $imagen_blob, $id_categoria);
        $stmt->execute();
        $id_destino = $conn->insert_id;

        $stmt = $conn->prepare("INSERT INTO servicios (nombre, descripcion, precio, duracion, id_categoria, id_destino, id_operador) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsiii", $nombre, $descripcion, $precio, $duracion, $id_categoria, $id_destino, $id_operador);
        $stmt->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Manejar eliminación
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM servicios WHERE id_destino = $id");
    $conn->query("DELETE FROM destinos WHERE id_destino = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Obtener datos
$categorias = $conn->query("SELECT * FROM categorias");
$destinos = $conn->query("
    SELECT d.*, c.nombre AS nombre_categoria 
    FROM destinos d 
    JOIN categorias c ON d.id_categoria = c.id_categoria
");

$destinoEditar = null;
if (isset($_GET['editar'])) {
    $id_editar = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM destinos WHERE id_destino = $id_editar");
    $destinoEditar = $res->fetch_assoc();

    $res_serv = $conn->query("SELECT * FROM servicios WHERE id_destino = $id_editar");
    if ($res_serv->num_rows > 0) {
        $servicioEditar = $res_serv->fetch_assoc();
        $destinoEditar['precio'] = $servicioEditar['precio'];
        $destinoEditar['duracion'] = $servicioEditar['duracion'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Destinos</title>
  <link rel="stylesheet" href="../css/modificar_MVC.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
<header class="header">
    <div class="container">
        <div class="btn-menu">
            <label for="btn-menu">☰</label>
        </div>
        <nav class="menu">
            <a href="administrador.php">Inicio</a>
            <a href="../public/paises.php">Países</a>
            <a href="modificar MVC.php">Destinos</a>
            <a href="../public/blog.php">Sugerencias</a>
        </nav>
    </div>
</header>


<div class="capa"></div>
<input type="checkbox" id="btn-menu" />
<div class="container-menu">
    <div class="cont-menu">
        <nav>
            <ul>
                <li><a href="usuarios.php">Usuarios</a></li>
                <li><a href="../public/bitacora.php">Bitacora</a></li>
                <li><a href="reporte.php">Reportes</a></li>
                <li><a href="../public/politica.php">Politicas</a></li>
            </ul>
            <div class="logout-container">
             <a href="../auth/logout.php" id="logout-button" class="btn-logout">Cerrar Sesión</a>
            </div>

        </nav>
        <label for="btn-menu">✖️</label>
    </div>
</div>

<main class="container">
  <h2 class="mt-4">Destinos Turísticos</h2>
  <div class="destination-grid">
    <?php while ($row = $destinos->fetch_assoc()): ?>
      <div class="destination-card">
        <?php if ($row['imagen_blob']): ?>
          <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagen_blob']); ?>" class="icon" alt="">
        <?php else: ?>
          <img src="img destinos/default.jpg" class="icon" alt="Sin imagen">
        <?php endif; ?>
        <h4><?php echo htmlspecialchars($row['nombre']); ?></h4>
        <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
        <p><em>Categoría: <?php echo htmlspecialchars($row['nombre_categoria']); ?></em></p>
        <a href="?editar=<?php echo $row['id_destino']; ?>" class="btn btn-sm btn-warning">Editar</a>
        <a href="?eliminar=<?php echo $row['id_destino']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este destino?')">Eliminar</a>
      </div>
    <?php endwhile; ?>
  </div>
  <button class="btn btn-primary mb-3" onclick="abrirModal()">Nuevo Destino</button>
</main>

<!-- Modal -->
<div id="destinoModal" class="modal" style="<?php echo $destinoEditar ? 'display:block;' : 'display:none;'; ?>">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $destinoEditar ? 'Editar' : 'Nuevo'; ?> Destino</h5>
      
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data">
          <?php if ($destinoEditar): ?>
            <input type="hidden" name="id_destino" value="<?php echo $destinoEditar['id_destino']; ?>">
          <?php endif; ?>
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required value="<?php echo $destinoEditar['nombre'] ?? ''; ?>">
          </div>
          <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required><?php echo $destinoEditar['descripcion'] ?? ''; ?></textarea>
          </div>
          <div class="form-group">
            <label>Departamento</label>
            <input type="text" name="departamento" class="form-control" required value="<?php echo $destinoEditar['departamento'] ?? ''; ?>">
          </div>
          <div class="form-group">
            <label>Atractivos</label>
            <textarea name="atractivos" class="form-control" required><?php echo $destinoEditar['atractivos'] ?? ''; ?></textarea>
          </div>
          <div class="form-group">
            <label>Categoría</label>
            <select name="id_categoria" class="form-control" required>
              <option value="">Seleccione una categoría</option>
              <?php 
              $categorias->data_seek(0);
              while ($cat = $categorias->fetch_assoc()): ?>
                <option value="<?php echo $cat['id_categoria']; ?>" <?php if (($destinoEditar['id_categoria'] ?? '') == $cat['id_categoria']) echo 'selected'; ?>>
                  <?php echo htmlspecialchars($cat['nombre']); ?>
                </option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Precio del Servicio</label>
            <input type="number" step="0.01" name="precio" class="form-control" required value="<?php echo $destinoEditar['precio'] ?? ''; ?>">
          </div>
          <div class="form-group">
            <label>Duración del Servicio</label>
            <input type="text" name="duracion" class="form-control" required value="<?php echo $destinoEditar['duracion'] ?? ''; ?>">
          </div>
          <div class="form-group">
            <label>Imagen (JPG/PNG)</label>
            <input type="file" name="imagen" class="form-control-file">
          </div>
          <button type="submit" class="btn btn-success btn-block"><?php echo $destinoEditar ? 'Actualizar' : 'Guardar'; ?></button>
          <button type="button" class="btn btn-secondary btn-block" onclick="cerrarModal()">Cerrar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
        window.addEventListener('scroll', function() {
        var header = document.querySelector('.header');
        header.classList.toggle('scrolled', window.scrollY > 0);
      });
<?php if (isset($_GET['error']) && $_GET['error'] === 'destino_duplicado'): ?>
alert('Ya existe un destino turístico con ese nombre. Por favor elija otro nombre.');
var url = new URL(window.location);
url.searchParams.delete('error');
window.history.replaceState({}, '', url);
<?php endif; ?>
function abrirModal() {
  document.getElementById("destinoModal").style.display = "block";
}
function cerrarModal() {
  if (window.location.search.includes('editar=')) {
    window.location.href = window.location.pathname;
  } else {
    document.getElementById("destinoModal").style.display = "none";
  }
}
</script>

</body>
</html>
