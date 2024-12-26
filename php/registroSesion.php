<?php

session_start();


include ("conexion.php");

//declaro las variables donde se van a guardar los valores que se llenan en el formulario
//por conveniencia, les pongo el mismo nombre

//CONTROL DE SESIONES
//---------------------------------------------------------------------------------------------------
    
    // Verificar si los datos han sido enviados por el formulario
    if (isset($_POST['nombre_usuario'], $_POST['correo_usuario'], $_POST['clave_usuario'], $_POST['img'])) {
        // Capturar los datos del formulario
        $nombre_usuario = $_POST['nombre_usuario'];
        $correo_usuario = $_POST['correo_usuario'];
        $clave_usuario = $_POST['clave_usuario'];
        $img = $_POST['img'];

        // Consulta para verificar que los datos del usuario coincidan con la base de datos
        $iniciosesionConsulta = "SELECT * FROM usuarios WHERE correo_usuario='$correo_usuario' AND clave_usuario='$clave_usuario'";
        $iniciosesionConsulta = mysqli_query($conexion, $iniciosesionConsulta);

    }

    /*Los casos están hechos para las diferentes formas de tomar datos de cada sesión. 
    Para que no se sobreescriban los datos ni aparezcan errores 'undefined'*/
//----------------------------------------------------------------------------------------------------


//REGISTRO DE SESIÓN

        //si no hay un usuario ya registrado con ese nombre_usuario y correo
        if (mysqli_num_rows($iniciosesionConsulta) == 0) {
            $insert = "INSERT INTO usuarios(id_usuario, nombre_usuario, correo_usuario, clave_usuario, img) VALUES ('','$nombre_usuario','$correo_usuario','$clave_usuario','$img')";
            //entonces se inserta los valores de ese nuevo usuario
            mysqli_query($conexion, $insert);
            
            // Si los datos son correctos, establecer las variables de sesión
            $_SESSION['usuario'] = [
                'nombre' => $nombre_usuario,
                'correo' => $correo_usuario,
                'clave' => $clave_usuario

            ];

            $sesionIniciada = false;
            if (!isset($_SESSION['audio_played'])) {
                $sesionIniciada = true;
                $_SESSION['audio_played'] = true; // Marcar como reproducido
            }

            ?>
            <link rel="stylesheet" href="../css/styles.css">
            
            <link rel="icon" type="image/jpg" href="../img/icon.png"/>

            <video autoplay loop id="myVideo">
                <source src="../video/registro.mp4" type="video/mp4">
            </video>

            <div class="content">
                <h2 style="color: green; text-align: center;"> 
                    ¡Se ha registrado exitosamente!<br>
                    Redireccionando a SÍSIFO: <i> <?php echo $nombre_usuario ?>... Aguarde un momento
                </h2>
            </div>
                <audio src="../audio/registro.mp3" autoplay></audio>
            </div>
            
            <meta http-equiv="Refresh" content="10; url='interfazUsuario.php'" />
            <?php
            exit();
            
        }

        //si hay un usuario ya registrado con ese nombre_usuario y correo
        if (mysqli_num_rows($iniciosesionConsulta) > 0) { 
            ?>
            
            <meta http-equiv="Refresh" content="5; url='../registro.html'" />

            <h2 style="color: red; text-align: center;"> El nombre de usuario "<i>
                    <?php echo $nombre_usuario ?>
                </i>" o el correo "<i>
                    <?php echo $correo_usuario ?>
                </i>" ya se encuentra registrado.<br>
                Intente con otras credenciales.</h2>

            <!--Entonces no se inserta el nuevo suario y lo devuelve al registro-->
            
            <audio src="../audio/sad.mp3" autoplay></audio>

            <?php
        }
    