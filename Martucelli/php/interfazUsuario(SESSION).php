<?php
include("conexion.php");

session_start(); // Iniciar la sesión al principio

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Si los datos se envían mediante POST, usarlos para la autenticación
    if (isset($_POST['nombre_usuario']) && isset($_POST['correo_usuario'])) {
        $nombre_usuario = $_POST['nombre_usuario'];
        $correo_usuario = $_POST['correo_usuario'];
        
        // Guardar datos en la sesión
        $_SESSION['nombre_usuario'] = $nombre_usuario;
        $_SESSION['correo_usuario'] = $correo_usuario;
    } else {
        echo "Faltan datos en el formulario.";
        exit();
    }
} else if (isset($_SESSION['nombre_usuario']) && isset($_SESSION['correo_usuario'])) {
    // Si los datos están en la sesión, usarlos para la autenticación
    $nombre_usuario = $_SESSION['nombre_usuario'];
    $correo_usuario = $_SESSION['correo_usuario'];
} else {
    echo "Método de solicitud no permitido o datos faltantes.";
    exit();
}

// Usar consultas preparadas para mayor seguridad
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ? AND correo_usuario = ?");
$stmt->bind_param("ss", $nombre_usuario, $correo_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Usuario encontrado
    echo "<h1 style='color: green;'>EL USUARIO \"$nombre_usuario\" ACCEDIÓ EXITOSAMENTE</h1>";
    ?>
    <button><a href="../apiGmail/index.php">Google Gmail</a></button>
    <?php
} else {
    echo "<h1 style='color: red;'>El nombre de usuario o el correo electrónico son incorrectos</h1>";
}

$stmt->close();
mysqli_close($conexion);
?>
