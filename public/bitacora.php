<?php
include '../config/conexion.php';
// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
if (isset($_POST['backup'])) {
    $usuario = 'root';
    $password = '';  // Deja en blanco si no tienes contraseña
    $bd = 'turismo';
    $host = 'localhost';

    $archivo = "backup_" . date("Ymd_His") . ".sql";

    // Ruta ABSOLUTA donde se guardará el archivo
    $rutaArchivo = "C:\\xampp\\htdocs\\proyectoingsoft\\turismo\\backups\\$archivo";

    // Ruta RELATIVA para descarga (desde el navegador)
    $urlArchivo = "backups/$archivo";

    // Ruta al ejecutable mysqldump
    $mysqldump = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

    // Si no tienes contraseña: usa --password=
    $comando = "cmd /c \"$mysqldump -u$usuario --password= $bd > $rutaArchivo\"";

    exec($comando, $output, $result);

    if (file_exists($rutaArchivo) && filesize($rutaArchivo) > 0) {
        echo "<script>
            alert('✅ Backup creado exitosamente: $archivo');
            window.location.href = '$urlArchivo';
        </script>";
    } else {
        echo "<script>alert('❌ Error: el archivo no se creó o está vacío.');</script>";
    }
}






// Obtener la cantidad de registros por página
$registros_por_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $registros_por_pagina;

// --------- FILTROS ---------
$filtros = [];
if (!empty($_GET['buscar'])) {
    $buscar = $conn->real_escape_string($_GET['buscar']);
    $filtros[] = "(id_usuario LIKE '%$buscar%' OR accion LIKE '%$buscar%')";
}
if (!empty($_GET['fecha'])) {
    $fecha = $conn->real_escape_string($_GET['fecha']);
    $filtros[] = "DATE(fecha) = '$fecha'";
}

$where = "";
if (count($filtros) > 0) {
    $where = "WHERE " . implode(" AND ", $filtros);
}

// Consulta SQL
$sql = "SELECT id_bitacora, id_usuario, accion, fecha FROM bitacora $where ORDER BY fecha DESC LIMIT $inicio, $registros_por_pagina";
$result = $conn->query($sql);

$registros = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $registros[] = $row;
    }
}

// Total registros
$sql_total = "SELECT COUNT(*) as total FROM bitacora $where";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$total_registros = $row_total['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Cerrar conexión
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="icono.ico" type="image/x-icon">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Agencia de Viajes</title>
    <link rel="stylesheet" href="../css/bitacora.css" />
    <script src="index.js" defer></script>
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

<div class="bitacora">
    <hr>
    <h2>Bitácora de Eventos</h2>

    <!-- BOTÓN BACKUP -->
    <form method="post" style="margin-bottom: 15px;">
        <button type="submit" name="backup" class="backup-button">📦 Crear Backup de Base de Datos</button>
    </form>

    <!-- FILTROS -->
    <form method="GET" action="bitacora.php" class="filtros-buscador">
        <input type="text" name="buscar" placeholder="Buscar acción o usuario" value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">

        <label>Fecha:</label>
        <input type="date" name="fecha" value="<?php echo isset($_GET['fecha']) ? $_GET['fecha'] : ''; ?>">

        <button type="submit">Filtrar por fecha</button>
        <a href="bitacora.php" class="reset-button">Restablecer filtros</a>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID Bitácora</th>
                <th>ID Usuario</th>
                <th>Acción</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($registros) > 0) {
                foreach ($registros as $row) {
                    echo "<tr>";
                    echo "<td>{$row['id_bitacora']}</td>";
                    echo "<td>{$row['id_usuario']}</td>";
                    echo "<td>{$row['accion']}</td>";
                    echo "<td>{$row['fecha']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay registros en la bitácora.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- PAGINACIÓN -->
    <div class="pagination">
        <?php
        if ($pagina > 1) {
            echo "<a href='bitacora.php?pagina=" . ($pagina - 1) . "'>« Anterior</a>";
        }
        for ($i = 1; $i <= $total_paginas; $i++) {
            echo "<a href='bitacora.php?pagina=$i'>$i</a>";
        }
        if ($pagina < $total_paginas) {
            echo "<a href='bitacora.php?pagina=" . ($pagina + 1) . "'>Siguiente »</a>";
        }
        ?>
    </div>
</div>

<footer>
    <div class="footer-container">
        <div class="footer-section">
            <h2>Nuestros contactos</h2>
            <p>WhatsApp: +591 60933137</p>
            <p>Correo: agenciasdeviajes@gmail.com</p>
            <p>Programe una reunión y cotización con nosotros</p>
            <p class="certification">Rápido y al instante <span>Bolivia Corp.</span></p>
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