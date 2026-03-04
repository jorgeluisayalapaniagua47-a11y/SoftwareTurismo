<?php
$host = 'localhost';  // O la dirección de tu servidor de base de datos
$usuario = 'root';    // Usuario de la base de datos
$contraseña = '';     // Contraseña de la base de datos
$base_de_datos = 'turismo';  // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Verificar si hay algún error de conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
