<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container">
        <h1>Iniciar Sesión</h1>
        <form action="" method="POST">
            <input type="text" name="correo" placeholder="Correo" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
            
        </form>

        <a href="../index.php">Regresar</a>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once '../controllers/usuario_controller.php';

            $controller = new UsuarioController();
            $correo = $_POST['correo'];
            $password = $_POST['password'];

            // Validar credenciales
            $resultado = $controller->validarUsuario($correo, $password);
            if ($resultado) {
                // Iniciar sesión y redirigir según el rol
                session_start();
                $_SESSION['idUsuario'] = $resultado['idUsuario'];
                $_SESSION['rolUsuario'] = $resultado['rol'];

                switch ($resultado['rol']) {
                    case 'coordinador':
                        header("Location: ../views/coordinador_dashboard.php");
                        exit();
                    case 'auxiliar':
                        header("Location: ../views/auxiliar_dashboard.php");
                        exit();
                    case 'estudiante':
                        header("Location: ../views/estudiante_dashboard.php");
                        exit();
                    default:
                        echo "<p>Rol no reconocido.</p>";
                        break;
                }
            } else {
                echo "<p>Credenciales incorrectas.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
