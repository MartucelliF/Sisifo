<?php

include ("conexion.php");

//declaro las variables donde se van a guardar los valores que se llenan en el formulario
//por conveniencia, les pongo el mismo nombre

$nombre_usuario = $_POST["nombre_usuario"];//las variables van a almacenar el valor que recupera "POST" del valor del formulario
$correo_usuario = $_POST["correo_usuario"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php
    /*
    // Obtener todas las tablas de la base de datos rutina
    $consultaTablas = "SHOW TABLES FROM rutina";
    $resultadoTablas = mysqli_query($conexion, $consultaTablas);

    if ($resultadoTablas) {
        // Almacenar las tablas en un array
        $tablas = [];
        while ($fila = mysqli_fetch_row($resultadoTablas)) {
            $tablas[] = $fila[0];
        }
    } else {
        echo "Error al obtener las tablas: " . mysqli_error($conexion);
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mostrar Tablas de la BD</title>
    </head>

    <body>
        <h1>Tablas en la Base de Datos 'rutina'</h1>
        <?php if (!empty($tablas)): ?>
            <ul>
                <?php foreach ($tablas as $tabla): ?>
                    <li><?php echo htmlspecialchars($tabla); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No se encontraron tablas en la base de datos.</p>
        <?php endif; ?>
    </body>

    </html>
    <?php
    */


    //1° Guardo las consultas en variables
//Para el INICIO DE SESIÓN me tengo que asegurar que tanto el 'nombre_usuario' como 'correo_usuario' coincidan con el de un usuario
    $iniciosesionConsulta = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' && correo_usuario = '$correo_usuario'";
    //Para el REGISTRO me tengo que asegurar que el 'nombre_usuario' NI el 'correo_usuario' pertenezcan ya a algún usuario
    $registroConsulta = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' || correo_usuario = '$correo_usuario'";

    //2° Ejecuto las consultas en la BD y sobreescribo lo obtenido en esas mismas variables
    $iniciosesionConsulta = mysqli_query($conexion, $iniciosesionConsulta);
    $registroConsulta = mysqli_query($conexion, $registroConsulta);

    /*
    ?>
    <h2> nombreUsuario= "<?php echo $nombre_usuario ?>"</h2>
    <h2> correoUsuario= "<?php echo $correo_usuario ?>"</h2>


    //3° mysqli_num_rows determina la cantidad de filas acorde a ese usuario, si se está registrando, no debería haber ninguna. Por eso "0"
    */

    //INICIO DE SESIÓN
//Desde 'index.html' se buscaa enviar al 'paso = 0'
    if (!isset($_GET['paso']) || $_GET['paso'] == 0) {
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
            ?>
                <h1 style="color: green;"> EL USUARIO "<?php echo $nombre_usuario ?>" ACCEDIÓ EXITOSAMENTE</h2>
                    <audio src="../audio/epic.mp3" autoplay></audio>
                    <?php
                    $listaTareas = "SELECT * FROM rutinaview WHERE nombre_usuario='$nombre_usuario'";
                    $listaTareas = mysqli_query($conexion, $listaTareas);
                    
                    if (mysqli_num_rows($listaTareas) == 0) {

                        ?>NO TIENE TAREAS REGISTRADAS.<br>
                        <form action="crearTarea.php?paso=0" method="post">
                            <input type="submit" name="comun2" value="¿Le gustaría crear tareas?">
                            <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                            <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                        <?php

                    } else {
                        ?>
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
                                // Rellena las filas de la tabla con los datos obtenidos de la consulta
                                while ($fila = mysqli_fetch_assoc($listaTareas)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $fila['id_tarea']; ?></td>
                                        <td><?php echo $fila['nombre_subcategoria']; ?></td>
                                        <td><?php echo $fila['turno']; ?></td>
                                        <td><?php echo $fila['estado']; ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    }
        }
    }
    //REGISTRO DE SESIÓN
//Desde 'index.html' se buscaa enviar al 'paso = 1'
    if (!isset($_GET['paso']) || $_GET['paso'] == 1) {
        if (mysqli_num_rows($registroConsulta) == 0) { //si no hay un usuario ya registrado con ese nombre_usuario y correo
            $insert = "INSERT INTO usuarios(id_usuario, nombre_usuario, correo_usuario) VALUES ('','$nombre_usuario','$correo_usuario')";
            //entonces se inserta los valores de ese nuevo usuario
            mysqli_query($conexion, $insert);
            ?>
                <audio src="../audio/epic.mp3" autoplay></audio>
                <h2 style="color: green; text-align: center;"> ¡Se ha registrado exitosamente!<br>
                    Su nombre de usuario es "<i><?php echo $nombre_usuario ?></f>" y su correo eletrónico
                        "<i><?php echo $correo_usuario ?></i>"<br></h2>

                <meta http-equiv="Refresh" content="5; url='../index.html'" />
                <?php
        }
        if (mysqli_num_rows($registroConsulta) > 0) { //si no hay un usuario ya registrado con ese nombre_usuario y correo
            ?>
                <meta http-equiv="Refresh" content="5; url='../registro.html'" />

                <h2 style="color: red; text-align: center;"> El nombre de usuario "<i><?php echo $nombre_usuario ?></i>" o el
                    correo "<i><?php echo $correo_usuario ?></i>" ya se encuentra registrado.<br>
                    Intente con otras credenciales.</h2>

                <audio src="../audio/sad.mp3" autoplay></audio>

                <?php
        }
    }
    ?>
</body>

</html>