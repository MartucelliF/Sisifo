<?php

session_start();

include ("conexion.php");

//declaro las variables donde se van a guardar los valores que se llenan en el formulario
//por conveniencia, les pongo el mismo nombre

//CONTROL DE SESIONES
//---------------------------------------------------------------------------------------------------

    // Verificar si los datos han sido enviados por el formulario
    if (isset($_POST['nombre_usuario'], $_POST['correo_usuario'])) {
        // Capturar los datos del formulario
        $nombre_usuario = $_POST['nombre_usuario'];
        $correo_usuario = $_POST['correo_usuario'];

        // Consulta para verificar que los datos del usuario coincidan con la base de datos
        $iniciosesionConsulta = "SELECT * FROM usuarios WHERE nombre_usuario='$nombre_usuario' AND correo_usuario='$correo_usuario'";
        $iniciosesionConsulta = mysqli_query($conexion, $iniciosesionConsulta);

    }

    /*Los casos están hechos para las diferentes formas de tomar datos de cada sesión. 
    Para que no se sobreescriban los datos ni aparezcan errores 'undefined'*/
//----------------------------------------------------------------------------------------------------


//REGISTRO DE SESIÓN
    //Desde 'index.html' se busca enviar al 'paso = 1'
    if (!isset($_GET['paso']) || $_GET['paso'] == 1) {
        if (mysqli_num_rows($iniciosesionConsulta) == 0) { //si no hay un usuario ya registrado con ese nombre_usuario y correo
            $insert = "INSERT INTO usuarios(id_usuario, nombre_usuario, correo_usuario) VALUES ('','$nombre_usuario','$correo_usuario')";
            //entonces se inserta los valores de ese nuevo usuario
            mysqli_query($conexion, $insert);
            ?>
            <audio src="../audio/epic.mp3" autoplay></audio>
            <h2 style="color: green; text-align: center;"> ¡Se ha registrado exitosamente!<br>
                Su nombre de usuario es "<i>
                    <?php echo $nombre_usuario ?>
                    </f>" y su correo eletrónico
                    "<i>
                        <?php echo $correo_usuario ?>
                    </i>"<br></h2>

            <meta http-equiv="Refresh" content="5; url='../index.html'" />
            <?php
        }
        if (mysqli_num_rows($iniciosesionConsulta) > 0) { //si hay un usuario ya registrado con ese nombre_usuario y correo
            ?>
            
            <meta http-equiv="Refresh" content="5; url='../registro.html'" />

            <h2 style="color: red; text-align: center;"> El nombre de usuario "<i>
                    <?php echo $nombre_usuario ?>
                </i>" o el
                correo "<i>
                    <?php echo $correo_usuario ?>
                </i>" ya se encuentra registrado.<br>
                Intente con otras credenciales.</h2>

            <audio src="../audio/sad.mp3" autoplay></audio>

            <?php
        }
    }