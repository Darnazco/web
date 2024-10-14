<?php
session_start();

// Eliminar todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir a la página de inicio (index.php)
header("Location: ../index.php");
exit();
?>
