<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Iniciar sesión

include '../config/conexion.php'; // Conexión a la base de datos

// Verificar si la conexión se ha establecido correctamente
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_POST['accion'] == 'login') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Consulta para verificar las credenciales del usuario (usamos alias para evitar problemas con ñ en el nombre de columna)
    $sqlLogin = "SELECT id_usuario, nombre, correo, contraseña AS pass_hash, id_rol FROM usuarios WHERE correo = ?";
    $stmtLogin = $conn->prepare($sqlLogin);
    $stmtLogin->bind_param("s", $correo);
    $stmtLogin->execute();
    $resultLogin = $stmtLogin->get_result();

    if ($resultLogin->num_rows > 0) {
        $usuario = $resultLogin->fetch_assoc();

        if (password_verify($password, $usuario['pass_hash'])) {
            // Guardar datos en sesión, incluyendo el rol
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['id_rol'] = (int) $usuario['id_rol']; // Asegurar que sea un entero

            // Redirigir según el rol
            switch ($_SESSION['id_rol']) {
                case 1:
                    header("Location: ../admin/administrador.php");
                    break;
                case 2:
                    header("Location: ../operador/operador.php");
                    break;
                case 3:
                    header("Location: ../usuario/u_destinos.php");
                    break;
                default:
                    echo "<script>alert('Rol no reconocido.'); window.history.back();</script>";
                    exit();
            }
            exit();
        } else {
            // Contraseña incorrecta
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
        }
    } else {
        // Correo no registrado
        echo "<script>alert('Correo no registrado.'); window.history.back();</script>";
    }
}

    
     elseif ($_POST['accion'] == 'registro') {
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $telefono = !empty($_POST['telefono']) ? $_POST['telefono'] : NULL;
        $direccion = !empty($_POST['direccion']) ? $_POST['direccion'] : NULL;
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];

        // Verificar que las contraseñas coincidan
        if ($password !== $confirmPassword) {
            echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
            exit();
        }

        // Cifrar la contraseña
        $passwordCifrada = password_hash($password, PASSWORD_DEFAULT);

        // Asegurarse de que el rol sea el número 3 (Turista)
        $id_rol = 3; // Aquí se establece el rol como 3 (Turista)

        // Insertar nuevo usuario en la base de datos con el rol 3
        $sqlUsuario = "INSERT INTO usuarios (nombre, correo, telefono, direccion, contraseña, id_rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtUsuario = $conn->prepare($sqlUsuario);
        $stmtUsuario->bind_param("sssssi", $nombre, $correo, $telefono, $direccion, $passwordCifrada, $id_rol);

        if ($stmtUsuario->execute()) {
            // Iniciar sesión automáticamente con el usuario recién creado
            $_SESSION['id_usuario'] = $conn->insert_id;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['id_rol'] = 3; // Turista

            // Redirigir al usuario a la página de inicio (ya autenticado)
            echo "<script>alert('Usuario registrado correctamente.'); window.location.href='../usuario/u_destinos.php';</script>";
        } else {
            // Error al registrar el usuario
            echo "<script>alert('Error al registrar el usuario: {$stmtUsuario->error}'); window.history.back();</script>";
        }

        $stmtUsuario->close();
    } else {
        echo "<script>alert('Acción no válida.'); window.history.back();</script>";
    }

?>
