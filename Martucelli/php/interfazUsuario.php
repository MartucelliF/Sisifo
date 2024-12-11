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
        $iniciosesionConsulta = mysqli_query($conexion, $consulta);

        if (mysqli_num_rows($iniciosesionConsulta) > 0) {
            // Si los datos son correctos, establecer las variables de sesión
            $_SESSION['usuario'] = [
                'nombre' => $nombre_usuario,
                'correo' => $correo_usuario
            ];

            // Redirigir al usuario a la interfaz de usuario
            header("Location: interfazUsuario.php");
            exit();
        } else {
            // Si los datos no son correctos, mostrar un mensaje de error
            die("Error: Los datos del usuario no son correctos.");
        }
    }

    // Procesar cierre de sesión
    if (isset($_GET['logout'])) {
        session_start();
        session_unset(); // Limpia todas las variables de la sesión
        session_destroy(); // Destruye la sesión
        setcookie(session_name(), '', time() - 3600, '/'); // Elimina la cookie de la sesión
        header("Location: ../index.html"); // Redirigir a la página de inicio (ajusta según tu estructura)
        exit;
    }

    /*Los casos están hechos para las diferentes formas de tomar datos de cada sesión. 
    Para que no se sobreescriban los datos ni aparezcan errores 'undefined'*/
//----------------------------------------------------------------------------------------------------

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

</head>

<body>
    <?php

    
    /*
    ?>
    <h2> nombreUsuario= "<?php echo $nombre_usuario ?>"</h2>
    <h2> correoUsuario= "<?php echo $correo_usuario ?>"</h2>


    //3° mysqli_num_rows determina la cantidad de filas acorde a ese usuario, si se está registrando, no debería haber ninguna. Por eso "0"
    */

    //INICIO DE SESIÓN
    //Desde 'index.html' se busca enviar al 'paso = 0'

    
    if (isset($_SESSION['usuario'])) {

        $nombre_usuario = $_SESSION['usuario']['nombre'];
        $correo_usuario = $_SESSION['usuario']['correo'];
        
        //1° Guardo las consultas en variables
        //Para el INICIO DE SESIÓN me tengo que asegurar que tanto el 'nombre_usuario' como 'correo_usuario' coincidan con el de un usuario
        $iniciosesionConsulta = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' && correo_usuario = '$correo_usuario'";

        //2° Ejecuto las consultas en la BD y sobreescribo lo obtenido en esas mismas variables
        $iniciosesionConsulta = mysqli_query($conexion, $iniciosesionConsulta);

        if (mysqli_num_rows($iniciosesionConsulta) == 0) { //si no hay un usuario ya registrado con ese nombre_usuario y correo
            //-------------------------------------------------------------------------------------------
    
            ?>

            <h1> BASE DE DATOS: <?php echo $BD ?> </h1>
            <h2 style="color: red;"> El nombre de usuario "<i><?php echo $nombre_usuario ?></i>" o el correo
                "<i><?php echo $correo_usuario ?></i>" es incorrecto</h2>
            <audio src="../audio/sad.mp3" autoplay></audio>

            <a href="../index.html"><button>Volver a intentar</button></a>

            <?php
            //-------------------------------------------------------------------------------------------
        } else if (mysqli_num_rows($iniciosesionConsulta) == 1) {
            
            $cont=+1;

            ?>
            
            <div class="centrar">

                <h1 style="color: green;"> EL USUARIO "<?php echo $nombre_usuario ?>" ACCEDIÓ EXITOSAMENTE</h2>
                    
                    <?php
                    if($cont>2){
                        ?>
                            <audio src="../audio/epic.mp3" autoplay></audio>
                        <?php
                    }
                    ?>    

                    <?php
                    //CONSULTA PARA OBTENER SU 'EXP' Y 'NIVEL'
                    $consultaEXP = "SELECT EXP FROM usuarios WHERE nombre_usuario='$nombre_usuario';";
                    $consultaEXP = mysqli_query($conexion, $consultaEXP);
                    $consultaEXP = mysqli_fetch_row($consultaEXP);
                    $EXP_usuario = $consultaEXP[0];

                    echo "<b>EXP</b>=" .$EXP_usuario;

                    $consultaNIVEL = "SELECT NIVEL FROM usuarios WHERE nombre_usuario='$nombre_usuario';";
                    $consultaNIVEL = mysqli_query($conexion, $consultaNIVEL);
                    $consultaNIVEL = mysqli_fetch_row($consultaNIVEL);
                    $NIVEL_usuario = $consultaNIVEL[0];

                    echo "<br><br>"."<b>NIVEL</b>=" .$NIVEL_usuario;


                    echo "<br><br>";

                    //CONSULTA PARA OBTENER LAS TAREAS DE ESE USUARIO
                    $consultalistaTareas = "SELECT * FROM rutinaview WHERE nombre_usuario='$nombre_usuario'";
                    $consultalistaTareas = mysqli_query($conexion, $consultalistaTareas);

                    if (mysqli_num_rows($consultalistaTareas) == 0) {

                        ?>NO TIENE TAREAS REGISTRADAS.<br>
                        <form action="gestionTareas.php?paso=0" method="post">
                            <input type="submit" name="comun2" value="¿Le gustaría crear tareas?">
                            <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                            <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                        <?php

                    } else {
                        ?>
                            <div class="centrar">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID_Tarea</th>
                                            <th>Nombre_Subcategoría</th>
                                            <th>Turno</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        // Almacenar todas las filas en un array
                                        $listaTareas = [];
                                        while ($fila = mysqli_fetch_assoc($consultalistaTareas)) {
                                            $listaTareas[] = $fila;
                                        }

                                        // Generar las filas de la tabla
                                        foreach ($listaTareas as $tarea) {
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($tarea['id_tarea']); ?></td>
                                                <td><?php echo htmlspecialchars($tarea['nombre_subcategoria']); ?></td>
                                                <td><?php echo htmlspecialchars($tarea['turno']); ?></td>
                                                <td>
                                                    <?php 
                                                    if ($tarea['estado'] == 'COMPLETADA') {
                                                        echo '<b style="color: rgb(41, 250, 41); background-color: black;">' . htmlspecialchars($tarea['estado']) . '</b>';
                                                    } else {
                                                        echo '<b style="color: orange; background-color: black;">' . htmlspecialchars($tarea['estado']) . '</b>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <form action="gestionTareas.php?paso=6" method="post">
                                                        <input type="submit" value="Completar">
                                                        <input type="hidden" name="id_tarea" value="<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php
                            

                            ?>
                            <form action="gestionTareas.php?paso=0" method="post">
                                <input type="submit" name="comun2" value="+">
                                <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                                <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                            </form>
                            <br>
                            <br>
                            <form action="gestionTareas.php?paso=5" method="post">
                                <input type="submit" name="comun2" value="Generar PDF">
                            </form>
                            <br>

                            <form action="" method="get" style="display:inline;">
                                <button type="submit" name="logout" value="1">CERRAR SESIÓN</button>
                            </form>
                            <!--------------------------------->
                    <?php
                    }
                    ?>
            </div>
        <?php
        }
    }
?>