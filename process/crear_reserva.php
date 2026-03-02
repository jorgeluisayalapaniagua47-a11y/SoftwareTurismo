<?php
session_start();
require_once '../config/conexion.php';

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    die("El usuario no está autenticado.");
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar si el id_usuario existe en la tabla usuarios
$sql_usuario = "SELECT id_usuario FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $id_usuario);
$stmt_usuario->execute();
$stmt_usuario->store_result();

if ($stmt_usuario->num_rows === 0) {
    die("El usuario con id $id_usuario no existe en la base de datos.");
}

// Obtener los datos del formulario
$fecha_reserva = $_POST['fecha_reserva'];
$cantidad_personas = $_POST['cantidad_personas'];
$total = $_POST['total'];
$id_servicio = isset($_POST['id_servicio']) ? $_POST['id_servicio'] : null;

// Verificar que los datos no estén vacíos
if (empty($fecha_reserva) || empty($cantidad_personas) || empty($total) || empty($id_servicio)) {
    die("Todos los campos son requeridos.");
}

// Iniciar una transacción
$conn->begin_transaction();

try {
    // 1. Insertar la reserva
    $sql_reserva = "INSERT INTO reservas (id_usuario, fecha_reserva, cantidad_personas, total) 
                    VALUES (?, ?, ?, ?)";
    $stmt_reserva = $conn->prepare($sql_reserva);
    $stmt_reserva->bind_param("isis", $id_usuario, $fecha_reserva, $cantidad_personas, $total);
    $stmt_reserva->execute();
    $id_reserva = $stmt_reserva->insert_id;

    // 2. Insertar en reserva_servicio
    $sql_reserva_servicio = "INSERT INTO reserva_servicio (id_reserva, id_servicio) VALUES (?, ?)";
    $stmt_reserva_servicio = $conn->prepare($sql_reserva_servicio);
    $stmt_reserva_servicio->bind_param("ii", $id_reserva, $id_servicio);
    $stmt_reserva_servicio->execute();

    // 3. Insertar en pagos
    $estado_pago = 'pendiente';
    $sql_pago = "INSERT INTO pagos (id_reserva, monto, estado_pago) VALUES (?, ?, ?)";
    $stmt_pago = $conn->prepare($sql_pago);
    $stmt_pago->bind_param("iis", $id_reserva, $total, $estado_pago);
    $stmt_pago->execute();
    $id_pago = $stmt_pago->insert_id;

    // 4. Actualizar la tabla reservas con id_servicio e id_pago
    $sql_update_reserva = "UPDATE reservas SET id_servicio = ?, id_pago = ? WHERE id_reserva = ?";
    $stmt_update_reserva = $conn->prepare($sql_update_reserva);
    $stmt_update_reserva->bind_param("iii", $id_servicio, $id_pago, $id_reserva);
    $stmt_update_reserva->execute();

    // 5. Insertar en la bitácora
    $accion = "Reserva creada con ID $id_reserva. Servicio ID: $id_servicio, Usuario ID: $id_usuario.";
    $sql_bitacora = "INSERT INTO bitacora (id_usuario, accion) VALUES (?, ?)";
    $stmt_bitacora = $conn->prepare($sql_bitacora);
    $stmt_bitacora->bind_param("is", $id_usuario, $accion);
    $stmt_bitacora->execute();

    // Confirmar la transacción
    $conn->commit();

    echo "Reserva y pago creados correctamente. ID de la reserva: $id_reserva.";

} catch (Exception $e) {
    $conn->rollback();
    echo "Error al crear la reserva o el pago: " . $e->getMessage();
}
?>
