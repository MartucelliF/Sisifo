<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <link rel="icon" type="image/jpg" href="../img/icon.png"/>

</head>

<body>
    <?php

    session_start();

    include ("conexion.php");

    //declaro las variables donde se van a guardar los valores que se llenan en el formulario
    //por conveniencia, les pongo el mismo nombre

    //CONTROL DE SESIONES
    //---------------------------------------------------------------------------------------------------
       
        // Verificar si los datos han sido enviados por el formulario
        if (isset($_POST['correo_usuario'], $_POST['clave_usuario'])) {
            // Capturar los datos del formulario
            $correo_usuario = $_POST['correo_usuario'];
            $clave_usuario = $_POST['clave_usuario'];

            // Consulta para verificar que los datos del usuario coincidan con la base de datos
            $iniciosesionConsulta = "SELECT * FROM usuarios WHERE correo_usuario='$correo_usuario' AND clave_usuario='$clave_usuario'";
            $iniciosesionConsulta = mysqli_query($conexion, $iniciosesionConsulta);

            if (mysqli_num_rows($iniciosesionConsulta) == 1) {
                $nombre_usuario= "SELECT nombre_usuario FROM usuarios WHERE correo_usuario='$correo_usuario' AND clave_usuario='$clave_usuario';";
                $nombre_usuario = mysqli_query($conexion, $nombre_usuario);
                $nombre_usuario = mysqli_fetch_row($nombre_usuario);
                $nombre_usuario = $nombre_usuario[0];

                // Si los datos son correctos, establecer las variables de sesión
                $_SESSION['usuario'] = [
                    'nombre' => $nombre_usuario,
                    'correo' => $correo_usuario,
                    'clave' => $clave_usuario
                ];
                
                // AUDIO AL INICIAR SESIÓN
                // Reproducir el sonido solo la primera vez
                $sesionIniciada = false;
                if (!isset($_SESSION['audio_played'])) {
                    $sesionIniciada = true;
                    $_SESSION['audio_played'] = true; // Marcar como reproducido
                }     
                
                // Redirigir al usuario a la interfaz de usuario
                header("Location: interfazUsuario.php");
                exit();

            } else {
                // Si los datos no son correctos, mostrar un mensaje de error

                ?>
                <h1> BASE DE DATOS: <?php echo $BD ?> </h1>
                <h2 style="color: red;"> El nombre de usuario "<i><?php echo $nombre_usuario ?></i>" o el correo
                    "<i><?php echo $correo_usuario ?></i>" es incorrecto</h2>
                <audio src="../audio/sad.mp3" autoplay></audio>
    
                <a href="../index.php"><button>Volver a intentar</button></a>
    
                <?php
            }
        }
?>