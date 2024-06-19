<?php

include("conexion.php");
$nombre_usuario = $_POST["nombre_usuario"];//las variables van a almacenar el valor que recupera "POST" del valor del formulario
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <title>CREAR TAREA: "<?php echo $nombre_usuario ?>"</title>
</head>
<body>
</body>
</html>

<?php

if (!isset($_GET['paso']) || $_GET['paso'] == 0) {
    
    //OBTENER 'id' DEL USUARIO PARA EL PASO 'SUBIDA DE DATOS'
    $consultaId_Usuario = "SELECT id_usuario FROM usuarios WHERE nombre_usuario='$nombre_usuario';";
    $consultaId_Usuario = mysqli_query($conexion, $consultaId_Usuario);
    $consultaId_Usuario = mysqli_fetch_row($consultaId_Usuario);//como los resultados funcionan por filas, obtengo el valor de cada fila
    $id_usuario = $consultaId_Usuario[0]; /*obtengo el valor específicamente de la primera fila de resultados 
    (la única que DEBERÍA haber por ser el 'nombre_usuario' ÚNICO por usuario)*/

    //CATEGORÍA #1
    //OBTENER EL NOMBRE DE TODAS LAS CATEGORÍAS
    $consultaOptionCategorias = "SELECT nombre_categoria FROM categorias;";
    $resultadoCategorias = mysqli_query($conexion, $consultaOptionCategorias);

    if (!$resultadoCategorias) {
        die("Error en la consulta de categorías: " . mysqli_error($conexion));
    }

    ?>

    <form action="crearTarea.php?paso=1" method="post">
        <h1>Formulario de Gestión de Tareas: CATEGORÍA</h1>
        <fieldset class="datosTarea">
            <label for="categoria">Categoría:</label>
            <select id="categoria" name="categoria" required>
                <?php
                            
                //CATEGORÍA #2
                //ASOCIO EL NOMBRE DE CADA CATEGORIA A LA FILA DEL ARRAY "FILA" MEDIANTE "ASSOC"
                while ($fila = mysqli_fetch_assoc($resultadoCategorias)) {
                    ?>
                    <option> <?php echo $fila['nombre_categoria'] ?> </option>';
                    <?php
                }
                ?>
            </select>
            <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
            <input type="submit" name="comun2" value="Seleccionar">
            <input type="reset" name="Limpiar" value="LIMPIAR">
            <br>
            <br>
            <label for="subcategoria">Subcategoría:</label>
            <select id="subcategoria" name="subcategoria">
            </select>
        </fieldset>
    </form>

    <?php
}

if (isset($_GET['paso']) && $_GET['paso'] == 1) {
    $categoria = $_POST['categoria'];
    $id_usuario = $_POST['id_usuario'];

    //echo $categoria;

    // Obtener nombre de las SUBCATEGORÍAS pertenecientes a esa categoría elegida
    $consultaOptionSubcategorias = "SELECT nombre_subcategoria FROM subcategorias, categorias WHERE nombre_categoria='$categoria' && subcategorias.id_categoria = categorias.id_categoria;";
    $consultaOptionSubcategorias = mysqli_query($conexion, $consultaOptionSubcategorias);


    if (!$consultaOptionSubcategorias) {
        die("Error en la consulta de subcategorías: " . mysqli_error($conexion));
    }

    ?>
    <form action="crearTarea.php?paso=2" method="post">
        <h1>Formulario de Gestión de Tareas: SUBCATEGORÍA</h1>
        <fieldset class="datosTarea">
            <label for="categoria">Categoría: <?php echo $categoria ?></label>
            <br>
            <br>
            <label for="subcategoria">Subcategoría:</label>
            <select id="subcategoria" name="subcategoria" required>
                <?php
                while ($fila = mysqli_fetch_assoc($consultaOptionSubcategorias)) {
                    ?>
                    <option> <?php echo $fila['nombre_subcategoria'] ?> </option>';
                    <?php
                }
                ?>
            </select>
            <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
            <input type="submit" name="comun2" value="Seleccionar">
            <input type="reset" name="Limpiar" value="LIMPIAR">
            <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
        </fieldset>
    </form>
    <?php
}
?>
<?php
if (isset($_GET['paso']) && $_GET['paso'] == 2) {
    $categoria = $_POST['categoria'];
    $subcategoria = $_POST['subcategoria'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $id_usuario = $_POST['id_usuario'];

    //echo $categoria;
    //echo $subcategoria;

    ?>
    <form action="crearTarea.php?paso=3" method="post">
        <h1>Formulario de Gestión de Tareas: TURNO</h1>
        <fieldset class="datosTarea">
            <label for="categoria">Categoría: <?php echo $categoria ?></label>
            <br>
            <br>
            <label for="subcategoria">Subcategoría: <?php echo $subcategoria ?></label>
            <br>
            <br>
            <label for="turno">Turno:</label>
            <select id="turno" name="turno" required>
                <option>Mañana</option>
                <option>Mediodía</option>
                <option>Tarde</option>
                <option>Noche</option>
            </select>
            <input type="submit" name="comun2" value="Seleccionar">
            <input type="reset" name="Limpiar" value="LIMPIAR">
        </fieldset>
        <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
        <input type="hidden" name="subcategoria" value="<?php echo $subcategoria; ?>">
    </form>
<?php
}
?>
<?php
if (isset($_GET['paso']) && $_GET['paso'] == 3) {
    $categoria = $_POST['categoria'];
    $subcategoria = $_POST['subcategoria'];
    $turno = $_POST['turno'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $id_usuario = $_POST['id_usuario'];

    /*
    echo $categoria;
    echo $subcategoria;
    echo $turno;
    */

    ?>

    <form action="crearTarea.php?paso=4" method="post">
        <h1>Formulario de Gestión de Tareas: SUBIDA DE DATOS</h1>
        <!--INSERCIÓN DE LA TAREA ESPECÍFICA PARA ESE USUARIO CON LOS DATOS DEFINIDOS HASTA AHORA-->

        <fieldset class="datosTarea">
            <label for="categoria">Categoría: <?php echo $categoria ?></label>
            <br>
            <br>
            <label for="subcategoria">Subcategoría: <?php echo $subcategoria ?></label>
            <br>
            <br>
            <label for="turno">Turno: <?php echo $turno ?></label>
            <br>
            <br>
            <label for="turno">Id Usuario: <?php echo $id_usuario ?></label>
        </fieldset>
        <br>
        <input type="submit" name="tarea" value="Subir tarea">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
        <input type="hidden" name="turno" value="<?php echo $turno; ?>">
        <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
        <input type="hidden" name="subcategoria" value="<?php echo $subcategoria; ?>">
    </form>

<?php
}
?>
<?php
if (isset($_GET['paso']) && $_GET['paso'] == 4) {
    $categoria = $_POST['categoria'];
    $subcategoria = $_POST['subcategoria'];
    $turno = $_POST['turno'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $id_usuario = $_POST['id_usuario'];
    
    //Consulto por el 'id_subcategoria' para lograr la referencia entre las tablas
    $id_subcategoria = "SELECT id_subcategoria FROM subcategorias WHERE nombre_subcategoria='$subcategoria';";
    $id_subcategoria = mysqli_query($conexion, $id_subcategoria);
    $id_subcategoria = mysqli_fetch_row($id_subcategoria);
    $id_subcategoria = $id_subcategoria[0];

    //Inserto los valores que se guardaron en las anteriores variables
    $insertTarea = "INSERT INTO tareas(id_tarea, id_usuario, id_subcategoria, turno, estado) VALUES ('','$id_usuario','$id_subcategoria','$turno','PENDIENTE');";

    //2° Ejecuto las consultas en la BD y sobreescribo lo obtenido en esas mismas variables
    $insertTarea = mysqli_query($conexion, $insertTarea);

}
?>