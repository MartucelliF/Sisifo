<?php

include ("conexion.php");

//declaro las variables donde se van a guardar los valores que se llenan en el formulario
//por conveniencia, les pongo el mismo nombre

$nombre_usuario = $_POST["nombre_usuario"];//las variables van a almacenar el valor que recupera "POST" del valor del formulario
$correo_usuario = $_POST["correo_usuario"];


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
$usuarioConsulta = "SELECT nombre_usuario FROM usuarios WHERE nombre_usuario = '$nombre_usuario'";
$correoConsulta = "SELECT correo_usuario FROM usuarios WHERE correo_usuario = '$correo_usuario'";

//2° Ejecuto las consultas en la BD y sobreescribo lo obtenido en esas mismas variables
$usuarioConsulta = mysqli_query($conexion, $usuarioConsulta);
$correoConsulta = mysqli_query($conexion, $correoConsulta);

/*
?>
<h2> nombreUsuario= "<?php echo $nombre_usuario ?>"</h2>
<h2> correoUsuario= "<?php echo $correo_usuario ?>"</h2>


//3° mysqli_num_rows determina la cantidad de filas acorde a ese usuario, si se está registrando, no debería haber ninguna. Por eso "0"
*/
if (mysqli_num_rows($usuarioConsulta) == 0 && mysqli_num_rows($correoConsulta) == 0) { //si no hay un usuario ya registrado con ese nombre_usuario y correo

    $insert = "INSERT INTO usuarios(id_usuario, nombre_usuario, correo_usuario) VALUES ('','$nombre_usuario','$correo_usuario')";
    //entonces se inserta los valores de ese nuevo usuario
    mysqli_query($conexion, $insert);
    //-------------------------------------------------------------------------------------------

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <h1> BASE DE DATOS: <?php echo $BD ?> </h1>
    <h2 style="color: green;"> SE REGISTRÓ "<?php echo $nombre_usuario ?>"</h2>
    <audio src="../audio/epic.mp3" autoplay></audio>

    <form action="crearTarea.php" name="" method="post">
            <br>
            <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
            <input type="submit" name="rutina" value="Crear tarea">
        </form>

    </html>
    <?php
    //-------------------------------------------------------------------------------------------
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1 style="color: red;"> EL USUARIO "<?php echo $nombre_usuario ?>" YA ESTÁ REGISTRADO</h2>
            <audio src="../audio/sad.mp3" autoplay></audio>
    </body>

        <form action="crearTarea.php" name="" method="post">
            <br>
            <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
            <input type="submit" name="rutina" value="Crear tarea">
        </form>

    </html>
    <?php
}
?>