<?php
session_start();
include '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['id_reserva'])) {
    $id_reserva = $_POST['id_reserva'];

    // Verificar conexión
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Primero, actualizar el estado de la reserva a 'Cancelada'
    $sql_reserva = "UPDATE reservas SET estado = 'cancelada' WHERE id_reserva = ?";
    if ($stmt = $conn->prepare($sql_reserva)) {
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Error en la consulta de actualización de reserva: " . $conn->error);
    }

    // Segundo, actualizar el estado del pago a 'fallido'
    $sql_pago = "UPDATE pagos SET estado_pago = 'fallido' WHERE id_reserva = ?";
    if ($stmt = $conn->prepare($sql_pago)) {
        $stmt->bind_param("i", $id_reserva);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Error en la consulta de actualización de pago: " . $conn->error);
    }

    // Redirigir después de la actualización
    header("Location: mis_reservas.php");
    exit();
}
?>
