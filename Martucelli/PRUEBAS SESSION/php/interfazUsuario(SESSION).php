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
    echo "DOU";
} else {
    echo "Método de solicitud no permitido o datos faltantes.";
    exit();
}

//1° Guardo las consultas en variables
    //Para el INICIO DE SESIÓN me tengo que asegurar que tanto el 'nombre_usuario' como 'correo_usuario' coincidan con el de un usuario
    $iniciosesionConsulta = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' && correo_usuario = '$correo_usuario'";
    //Para el REGISTRO me tengo que asegurar que el 'nombre_usuario' NI el 'correo_usuario' pertenezcan ya a algún usuario
    $registroConsulta = "SELECT * FROM usuarios WHERE nombre_usuario = '$nombre_usuario' || correo_usuario = '$correo_usuario'";

    //2° Ejecuto las consultas en la BD y sobreescribo lo obtenido en esas mismas variables
    $iniciosesionConsulta = mysqli_query($conexion, $iniciosesionConsulta);
    $registroConsulta = mysqli_query($conexion, $registroConsulta);

if ($registroConsulta->num_rows == 1) {
    // Usuario encontrado
    ?>
    <link rel="stylesheet" href="../css/styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <button><a href="../apiGmail/index.php">Google Gmail</a></button>
    <h1 style="color: green;"> EL USUARIO "<?php echo $nombre_usuario ?>" ACCEDIÓ EXITOSAMENTE</h2>
                    <audio src="../audio/epic.mp3" autoplay></audio>

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
                            <div id="redirigirSesion">
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
                                        while ($fila = mysqli_fetch_assoc($consultalistaTareas)) {//automáticamente 'mysqli_fetch_assoc' define a $fila como un array que almacena de forma temporal cada fila recuperada de la consutla
                                            $listaTareas[] = $fila;//no hace falta indicar con '$i' porque PHP automáticamente agrega cada fila a la siguiente
                                        }

                                        // Rellena las filas de la tabla con los datos obtenidos de la consulta
                            
                                        for ($i = 0; $i < count($listaTareas); $i++) {
                                            ?>
                                            <tr>
                                                <td><?php echo $listaTareas[$i]['id_tarea']; ?></td>
                                                <td><?php echo $listaTareas[$i]['nombre_subcategoria']; ?></td>
                                                <td><?php echo $listaTareas[$i]['turno']; ?></td>
                                                <td><?php if ($listaTareas[$i]['estado'] == 'COMPLETADA') {
                                                                        echo '<b style="color: rgb(41, 250, 41); background-color: black;">' . htmlspecialchars($listaTareas[$i]['estado']) . '</b>';
                                                                    } else {
                                                                        echo '<b style="color: red; background-color: black;">' . htmlspecialchars($listaTareas[$i]['estado']);
                                                                    }
                                                                ?></td>
                                                <td>
                                                    <form action="gestionTareas.php?paso=6" method="post">
                                                        <input type="submit" name="" value="Completar"><i class='bx bx-check'></i></input>
                                                        <input type="hidden" name="id_tarea"
                                                            value="<?php echo $listaTareas[$i]['id_tarea']; ?>">
                                                        <input type="hidden" name="nombre_subcategoria"
                                                            value="<?php echo $listaTareas[$i]['nombre_subcategoria']; ?>">
                                                        <input type="hidden" name="turno" value="<?php echo $listaTareas[$i]['turno']; ?>">
                                                        <input type="hidden" name="estado" value="<?php echo $listaTareas[$i]['estado']; ?>">
                                                        <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                                                        <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                                                    </form>
                                                    </button>
                                                </td>
                                            </tr>

                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>

                                <form action="gestionTareas.php?paso=0" method="post">
                                    <input type="submit" name="comun2" value="+">
                                    <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                                    <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                                </form>
                                <br>

                                <?php
                                //PRUEBA CON PDF
                                //ENVIAR EL VALOR DE UNA TAREA
                                //Guardo el valor de la primera tarea en otro array
                                if (count($listaTareas) > 0) {
                                    $primeraTarea[0] = $listaTareas[0]['id_tarea'];
                                    $primeraTarea[1] = $listaTareas[0]['nombre_subcategoria'];
                                    $primeraTarea[2] = $listaTareas[0]['turno'];
                                    $primeraTarea[3] = $listaTareas[0]['estado'];

                                    /*
                                    // Imprime los valores de la primera tarea
                                    echo $primeraTarea[0] . " ";
                                    echo $primeraTarea[1] . " ";
                                    echo $primeraTarea[2] . " ";
                                    echo $primeraTarea[3] . " ";
                                    */
                                }

                                //Guardo los valores en las variables que le voy a enviar a 'generarPDF()'
                                //Para 'nombre_categoria' necesito obtenerlo de una consulta a la BD con el dato de la subcategoría que ya tengo
                                $categoria = "SELECT nombre_categoria FROM categorias WHERE categorias.id_categoria = (SELECT id_categoria FROM subcategorias WHERE nombre_subcategoria='$primeraTarea[1]');";
                                $categoria = mysqli_query($conexion, $categoria);
                                $categoria = mysqli_fetch_row($categoria);
                                $nombre_categoria = $categoria[0];
                                //-----------------------------------------------------------------------------
                                $subcategoria = $primeraTarea[1];
                                $turno = $primeraTarea[2];
                                //-----------------------------------------------------------------------------
                                ?>
                                <br>
                                <form action="gestionTareas.php?paso=5" method="post">
                                    <input type="submit" name="comun2" value="Generar PDF">
                                    <input type="hidden" name="subcategoria" value="<?php echo $subcategoria; ?>">
                                    <input type="hidden" name="turno" value="<?php echo $turno; ?>">
                                    <input type="hidden" name="categoria" value="<?php echo $nombre_categoria; ?>">
                                    <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                                    <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                                </form>
                                <br>
                                <form action="../apiGmail/index.php?recibedatos=1">
                                    <input type="submit" name="comun2" value="Google Gmail">
                                    <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                                    <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                                </form>    
                                <form action="../apiCalendar/index.php?recibedatos=1">
                                    <input type="submit" name="comun2" value="Google Calendar">
                                    <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                                    <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                                </form>  

                                <!--------------------------------->
                    </div>
                    <?php
                    }
} else {
        //-------------------------------------------------------------------------------------------
    
        ?>

        <h1> BASE DE DATOS: <?php echo $BD ?> </h1>
        <h2 style="color: red;"> El nombre de usuario "<i><?php echo $nombre_usuario ?></i>" o el correo
        "<i><?php echo $correo_usuario ?></i>" es incorrecto</h2>
        <audio src="../audio/sad.mp3" autoplay></audio>
    
        <a href="../index.html"><button>Volver a intentar</button></a>
    
        <?php
        //-------------------------------------------------------------------------------------------
}

mysqli_close($conexion);
?>
