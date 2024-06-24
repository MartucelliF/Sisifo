<?php

include("conexion.php");
$nombre_usuario = $_POST["nombre_usuario"];//las variables van a almacenar el valor que recupera "POST" del valor del formulario
$correo_usuario = $_POST["correo_usuario"];

echo $correo_usuario;
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
        <h1>Formulario de Gestión de Tareas: 0/4</h1>
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
            <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
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
        <h1>Formulario de Gestión de Tareas: 1/4 </h1>
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
            <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
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
        <h1>Formulario de Gestión de Tareas: 2/4 </h1>
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
        <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
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
        <h1>Formulario de Gestión de Tareas: 3/4 </h1>
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
        <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
        <input type="hidden" name="subcategoria" value="<?php echo $subcategoria; ?>">
        <input type="hidden" name="turno" value="<?php echo $turno; ?>">
        <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
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
    $correo_usuario = $_POST['correo_usuario'];

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
<?php
if (isset($_GET['paso']) && $_GET['paso'] == 5) {
    $categoria = $_POST['categoria'];
    $subcategoria = $_POST['subcategoria'];
    $turno = $_POST['turno'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo_usuario = $_POST['correo_usuario'];
    ?>

    <h1>Formulario de Gestión de Tareas: 4/4</h1>
    <button type="button" onclick="generarPDF()">Generar PDF</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function toggleExtracurriculares(show) {
            document.getElementById('extracurriculares_detalle').classList.toggle('hidden', !show);
        }

        function generarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            var nombre_usuario = "<?php echo $nombre_usuario; ?>";
            var categoria = "<?php echo $categoria; ?>";
            var subcategoria = "<?php echo $subcategoria; ?>";
            var correo_usuario = "<?php echo $correo_usuario; ?>";
            var turno = "<?php echo $turno; ?>";

            // Título centrado y subrayado
            const titulo = `Rutinas (${nombre_usuario})`;
            const pageWidth = doc.internal.pageSize.getWidth();
            const textWidth = doc.getTextWidth(titulo);
            const xPos = (pageWidth - textWidth) / 2;
            doc.text(titulo, xPos, 10);
            doc.setLineWidth(0.5);
            doc.line(xPos, 12, xPos + textWidth, 12);

            // Espacio adicional entre el título y la información del usuario
            const yOffset = 30;
            const lineHeight = 10; // Altura de cada línea

            // Información del usuario con suficiente espacio entre líneas
            let currentY = 10 + yOffset;
            doc.text(`Nombre: ${nombre_usuario}`, 10, currentY);
            currentY += lineHeight;
            doc.text(`Correo: ${correo_usuario}`, 10, currentY);
            currentY += lineHeight;
            
            // Función para dibujar cuadrados
            function drawSquare(doc, x, y, size) {
                doc.rect(x, y, size, size);
            }

            // Secciones de la rutina con cuadrados
            const sectionYOffset = currentY + 30;
            const squareSize = 5; // Tamaño del cuadrado

            // Mañana
            doc.text('Mañana (6:00 - 12:00)', 10, sectionYOffset);
            drawSquare(doc, 10, sectionYOffset + 10, squareSize);
            drawSquare(doc, 10, sectionYOffset + 20, squareSize);

            // Tarde
            doc.text('Tarde (12:00 - 19:00)', 10, sectionYOffset + 40);
            drawSquare(doc, 10, sectionYOffset + 50, squareSize);
            drawSquare(doc, 10, sectionYOffset + 60, squareSize);

            // Noche
            doc.text('Noche (19:00 - 24:00)', 10, sectionYOffset + 80);
            drawSquare(doc, 10, sectionYOffset + 90, squareSize);
            drawSquare(doc, 10, sectionYOffset + 100, squareSize);

            // Variables de control de altura
            let currentYOffsetMorning = sectionYOffset + 10;
            let currentYOffsetAfternoon = sectionYOffset + 50;
            let currentYOffsetNight = sectionYOffset + 90;

            // Obtener las tareas
            <?php
            $consultaTareas = "SELECT DISTINCT rutinaview.id_tarea, rutinaview.nombre_subcategoria, rutinaview.turno, nombre_categoria FROM rutinaview, categorias, subcategorias, tareas WHERE nombre_usuario='$nombre_usuario' && categorias.id_categoria = subcategorias.id_categoria && subcategorias.id_categoria = tareas.id_subcategoria;";
            $result = mysqli_query($conexion, $consultaTareas);

            $result = mysqli_query($conexion, $consultaTareas);
            if ($result && mysqli_num_rows($result) > 0) {
                while ($fila = mysqli_fetch_assoc($result)) {
                    $categoria_tarea = $fila['nombre_categoria'];
                    $subcategoria_tarea = $fila['nombre_subcategoria'];
                    $turno_tarea = $fila['turno'];
                    ?>
                    if (`<?php echo $turno_tarea; ?>` === 'Mañana') {
                        doc.text(`Tarea: <?php echo $categoria_tarea; ?> --> (<?php echo $subcategoria_tarea; ?>)`, 10, currentYOffsetMorning);
                        currentYOffsetMorning += lineHeight;
                    } else if (`<?php echo $turno_tarea; ?>` === 'Tarde') {
                        doc.text(`Tarea: <?php echo $categoria_tarea; ?> --> (<?php echo $subcategoria_tarea; ?>)`, 10, currentYOffsetAfternoon);
                        currentYOffsetAfternoon += lineHeight;
                    } else if (`<?php echo $turno_tarea; ?>` === 'Noche') {
                        doc.text(`Tarea: <?php echo $categoria_tarea; ?> --> (<?php echo $subcategoria_tarea; ?>)`, 10, currentYOffsetNight);
                        currentYOffsetNight += lineHeight;
                    }
                    <?php
                }
            }
            ?>
            // Guardar el archivo como "nombre_rutina.pdf"
            doc.save(`${nombre_usuario}_rutina.pdf`);
            
        }
        
    </script>

    <?php
}
if (isset($_GET['paso']) && $_GET['paso'] == 6) {
    $id_tarea = $_POST['id_tarea'];
    $nombre_subcategoria = $_POST['nombre_subcategoria'];
    $turno = $_POST['turno'];
    $estado = $_POST['estado'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo_usuario = $_POST['correo_usuario'];
    echo "<br><br>";
    echo "CONECTADO PAPU";
    
    $setCompletada = "UPDATE rutinaview SET estado = 'COMPLETADA' WHERE id_tarea = $id_tarea;";
    $setCompletada = mysqli_query($conexion,$setCompletada);
    
    echo $nombre_usuario;
    echo $correo_usuario;

    $crearTareaphpSESION = 1;

    $redirect_url = "interfazUsuario.php?paso=0&iniciosesionConsulta=1&consultalistaTareas=0&nombre_usuario=" . urlencode($nombre_usuario) . "&correo_usuario=" . urlencode($correo_usuario). "&crearTareaphpSESION=" . urlencode($crearTareaphpSESION) . "#redirigirSesion";
    ?>
    <meta http-equiv="refresh" content="5;url=<?php echo $redirect_url; ?>" />
    <?php
}
?>

</body>
</html>


        