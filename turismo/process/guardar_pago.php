<?php
include '../config/conexion.php';

$id_servicio = $_POST['id_servicio'];
$nombre = $_POST['nombre_cliente'];
$fecha = $_POST['fecha_reserva'];
$metodo = $_POST['metodo_pago'];

// Primero insertar en reserva
mysqli_query($conexion, "INSERT INTO reserva (nombre_cliente, id_servicio, fecha, metodo_pago)
VALUES ('$nombre', $id_servicio, '$fecha', '$metodo')");
header("Location: pagos.php?id_servicio=$id_servicio");
echo "¡Reserva y pago registrado con éxito!";
?>
