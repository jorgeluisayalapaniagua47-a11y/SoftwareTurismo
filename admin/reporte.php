<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_de_datos = "turismo";
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);
if ($conn->connect_error) {
    die("La conexión a la base de datos ha fallado: " . $conn->connect_error);
}

$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;

$page_ventas = isset($_GET['page_ventas']) ? (int)$_GET['page_ventas'] : 1;
$page_visitas = isset($_GET['page_visitas']) ? (int)$_GET['page_visitas'] : 1;
$por_pagina = 10;

function obtenerVentas($fecha_inicio, $fecha_fin, $pagina, $por_pagina)
{
    global $conn;
    $offset = ($pagina - 1) * $por_pagina;
    $where = "";

    if ($fecha_inicio && $fecha_fin) {

        if ($fecha_fin < $fecha_inicio) {
            echo "<div class='alert alert-danger'>
                    Error: La fecha final no puede ser menor que la fecha inicial.
                  </div>";
        } else {
            $fecha_inicio = $conn->real_escape_string($fecha_inicio);
            $fecha_fin = $conn->real_escape_string($fecha_fin);
            $where = "WHERE r.fecha_reserva BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }
    }

    $sql_total = "
        SELECT COUNT(DISTINCT r.fecha_reserva) AS total
        FROM reservas r
        JOIN reserva_servicio rs ON r.id_reserva = rs.id_reserva
        JOIN servicios s ON rs.id_servicio = s.id_servicio
        $where";
    $total_result = $conn->query($sql_total);
    $total_filas = $total_result->fetch_assoc()['total'];
    $total_paginas = ceil($total_filas / $por_pagina);

    $sql = "
        SELECT r.fecha_reserva, SUM(s.precio) AS total_ventas
        FROM reservas r
        JOIN reserva_servicio rs ON r.id_reserva = rs.id_reserva
        JOIN servicios s ON rs.id_servicio = s.id_servicio
        $where
        GROUP BY r.fecha_reserva
        ORDER BY r.fecha_reserva DESC
        LIMIT $offset, $por_pagina";
    $result = $conn->query($sql);

    echo '<div class="reporte-container">';
    echo '<h2>Reporte de Ventas por Fecha de Reserva</h2>';
    if ($result && $result->num_rows > 0) {
        echo '<table class="reporte-table">
                <tr><th>Fecha de Reserva</th><th>Total de Ventas (Bs)</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr><td>' . date("d/m/Y", strtotime($row['fecha_reserva'])) . '</td>
                      <td>' . number_format($row['total_ventas'], 2, '.', ',') . '</td></tr>';
        }
        echo '</table>';

        echo '<div class="pagination">';
        for ($i = 1; $i <= $total_paginas; $i++) {
            $active = ($i == $pagina) ? 'active' : '';
            $url = "?page_ventas=$i&page_visitas=$pagina";
            if ($fecha_inicio && $fecha_fin && $fecha_fin >= $fecha_inicio) {
                $url .= "&fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin";
            }
            echo "<a class='$active' href='$url'>$i</a> ";
        }
        echo '</div>';
    } else {
        echo '<p>No hay ventas registradas.</p>';
    }
    echo '</div>';
}

function obtenerVisitas($fecha_inicio, $fecha_fin, $pagina, $por_pagina)
{
    global $conn;
    $offset = ($pagina - 1) * $por_pagina;
    $where = "";

    if ($fecha_inicio && $fecha_fin) {

        if ($fecha_fin < $fecha_inicio) {
            echo "<div class='alert alert-danger'>
                    Error: La fecha final no puede ser menor que la fecha inicial.
                  </div>";
        } else {
            $fecha_inicio = $conn->real_escape_string($fecha_inicio);
            $fecha_fin = $conn->real_escape_string($fecha_fin);
            $where = "WHERE fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'";
        }
    }

    $sql_total = "SELECT COUNT(DISTINCT fecha) AS total FROM bitacora $where";
    $total_result = $conn->query($sql_total);
    $total_filas = $total_result->fetch_assoc()['total'];
    $total_paginas = ceil($total_filas / $por_pagina);

    $sql = "SELECT DATE(fecha) AS fecha, COUNT(*) AS total_visitas
            FROM bitacora
            $where
            GROUP BY DATE(fecha)
            ORDER BY fecha DESC
            LIMIT $offset, $por_pagina";

    $result = $conn->query($sql);

    echo '<div class="reporte-container">';
    echo '<h2>Reporte de Visitas por Fecha</h2>';
    if ($result && $result->num_rows > 0) {
        echo '<table class="reporte-table">
                <tr><th>Fecha</th><th>Total de Visitas</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr><td>' . date("d/m/Y", strtotime($row['fecha'])) . '</td>
                      <td>' . $row['total_visitas'] . '</td></tr>';
        }
        echo '</table>';

        echo '<div class="pagination">';
        for ($i = 1; $i <= $total_paginas; $i++) {
            $active = ($i == $pagina) ? 'active' : '';
            $url = "?page_visitas=$i&page_ventas=$pagina";
            if ($fecha_inicio && $fecha_fin && $fecha_fin >= $fecha_inicio) {
                $url .= "&fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin";
            }
            echo "<a class='$active' href='$url'>$i</a> ";
        }
        echo '</div>';
    } else {
        echo '<p>No hay visitas registradas.</p>';
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Reportes Agencia</title>
    <link rel="stylesheet" href="../css/reporte.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <main>
        <br>
        <h1>Reportes Estadísticos</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalFiltro">Filtrar por Fecha</button>

        <?php
        obtenerVentas($fecha_inicio, $fecha_fin, $page_ventas, $por_pagina);
        obtenerVisitas($fecha_inicio, $fecha_fin, $page_visitas, $por_pagina);
        ?>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="modalFiltro" tabindex="-1" aria-labelledby="modalFiltroLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="GET" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFiltroLabel">Filtrar por Fecha</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio:</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                        <label for="fecha_fin" class="form-label mt-2">Fecha de fin:</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p style="text-align:center; padding: 15px;">&copy; 2024 Agencia de Viajes. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conn->close(); ?>