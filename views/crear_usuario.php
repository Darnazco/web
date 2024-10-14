<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="../css/crear_usuario.css">
</head>
<body>
    <div class="container">
        <h1>Crear Usuario</h1>
        <form action="" method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="text" name="telefono" placeholder="Teléfono" required>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="hidden" name="rol" value="estudiante"> <!-- Rol siempre "estudiante" -->
            <input type="hidden" name="estado" value="1"> <!-- Estado siempre activo -->
            <button type="submit">Registrar</button>
            <a href="../index.php">Regresar</a>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once '../controllers/usuario_controller.php';

            $controller = new UsuarioController();
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $correo = $_POST['correo'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $password = $_POST['password'];
            $rol = "estudiante"; // Asignar rol "estudiante" directamente
            $estado = 1; // Asignar estado activo directamente

            if ($controller->crearUsuario($nombre, $apellido, $correo, $telefono, $direccion, $password, $rol, $estado)) {
                echo "<p>Usuario registrado exitosamente.</p>";
            } else {
                echo "<p>Error al registrar el usuario.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
