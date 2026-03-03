<?php
header('Content-Type: application/json');
session_start();
include '../config/conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Validar que se haya enviado el formulario por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_reserva']) || !isset($_POST['metodo_pago'])) {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado o falta información']);
    exit();
}

$id_reserva = $_POST['id_reserva'];
$metodo_pago = $_POST['metodo_pago'] ?? null;

if (!$metodo_pago) {
    echo json_encode(['success' => false, 'message' => 'Método de pago no especificado']);
    exit();
}

// Verificar que la reserva pertenezca al usuario
$sql_reserva = "SELECT * FROM reservas WHERE id_reserva = ? AND id_usuario = ?";
$stmt_reserva = $conn->prepare($sql_reserva);
$stmt_reserva->bind_param("ii", $id_reserva, $id_usuario);
$stmt_reserva->execute();
$reserva_result = $stmt_reserva->get_result();

if ($reserva_result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'No tienes acceso a esta reserva']);
    exit();
}

$row_reserva = $reserva_result->fetch_assoc();
$monto_total = $row_reserva['total']; // Asegúrate de que la columna 'total' exista
$fecha_pago = date('Y-m-d H:i:s');

// Iniciar transacción para asegurar consistencia de datos
$conn->begin_transaction();

try {
    // Verificar si ya existe un pago pendiente
    $sql_check_pago = "SELECT * FROM pagos WHERE id_reserva = ? AND estado_pago = 'pendiente'";
    $stmt_check = $conn->prepare($sql_check_pago);
    $stmt_check->bind_param("i", $id_reserva);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Si hay un pago pendiente, actualizarlo
        $sql_update_pago = "
            UPDATE pagos
            SET estado_pago = 'completado', fecha_pago = ?, metodo_pago = ?, monto = ?
            WHERE id_reserva = ? AND estado_pago = 'pendiente'
        ";
        $stmt_update = $conn->prepare($sql_update_pago);
        $stmt_update->bind_param("ssdi", $fecha_pago, $metodo_pago, $monto_total, $id_reserva);
        $stmt_update->execute();
    } else {
        // Si no hay pago pendiente, insertar uno nuevo
        $sql_insert_pago = "
            INSERT INTO pagos (id_reserva, monto, fecha_pago, metodo_pago, estado_pago)
            VALUES (?, ?, ?, ?, 'completado')
        ";
        $stmt_insert = $conn->prepare($sql_insert_pago);
        $stmt_insert->bind_param("idss", $id_reserva, $monto_total, $fecha_pago, $metodo_pago);
        $stmt_insert->execute();
    }

    // Actualizar el estado de la reserva a 'confirmada'
    $sql_update_reserva = "UPDATE reservas SET estado = 'confirmada' WHERE id_reserva = ?";
    $stmt_update_reserva = $conn->prepare($sql_update_reserva);
    $stmt_update_reserva->bind_param("i", $id_reserva);
    $stmt_update_reserva->execute();

    // Si todo sale bien, hacer commit
    $conn->commit();
    echo json_encode(['success' => true, 'message' => '✅ Pago realizado con éxito y reserva confirmada.']);
} catch (Exception $e) {
    // Si ocurre un error, realizar un rollback
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => '❌ Error al procesar el pago: ' . $e->getMessage()]);
}

$conn->close();
?>
