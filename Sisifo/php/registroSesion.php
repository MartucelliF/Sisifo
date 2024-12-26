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
        $img = $_POST['img'];

        // Consulta para verificar que los datos del usuario coincidan con la base de datos
        $iniciosesionConsulta = "SELECT * FROM usuarios WHERE nombre_usuario='$nombre_usuario' AND correo_usuario='$correo_usuario'";
        $iniciosesionConsulta = mysqli_query($conexion, $iniciosesionConsulta);

    }

    /*Los casos están hechos para las diferentes formas de tomar datos de cada sesión. 
    Para que no se sobreescriban los datos ni aparezcan errores 'undefined'*/
//----------------------------------------------------------------------------------------------------


//REGISTRO DE SESIÓN

        //si no hay un usuario ya registrado con ese nombre_usuario y correo
        if (mysqli_num_rows($iniciosesionConsulta) == 0) {
            $insert = "INSERT INTO usuarios(id_usuario, nombre_usuario, correo_usuario, img) VALUES ('','$nombre_usuario','$correo_usuario','$img')";
            //entonces se inserta los valores de ese nuevo usuario
            mysqli_query($conexion, $insert);
            
            // Si los datos son correctos, establecer las variables de sesión
            $_SESSION['usuario'] = [
                'nombre' => $nombre_usuario,
                'correo' => $correo_usuario
            ];

            ?>
            <audio src="../audio/epic.mp3" autoplay></audio>
            <h2 style="color: green; text-align: center;"> ¡Se ha registrado exitosamente!<br>
                Su nombre de usuario es "<i>
                    <?php echo $nombre_usuario ?>
                    </f>" y su correo eletrónico
                    "<i>
                        <?php echo $correo_usuario ?>
                    </i>"<br></h2>
            
            <?php
            // Redirigir al usuario a la interfaz de usuario
            header("Location: interfazUsuario.php");
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
    