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

    <form action="gestionTareas.php?paso=1" method="post">
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
    <form action="gestionTareas.php?paso=2" method="post">
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
    <form action="gestionTareas.php?paso=3" method="post">
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

    <form action="gestionTareas.php?paso=4" method="post">
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

    $crearTareaphpSESION = 1;

    $redirect_url = "interfazUsuario.php?paso=0&iniciosesionConsulta=1&consultalistaTareas=0&nombre_usuario=" . urlencode($nombre_usuario) . "&correo_usuario=" . urlencode($correo_usuario). "&crearTareaphpSESION=" . urlencode($crearTareaphpSESION) . "#redirigirSesion";
    
    ?>
    <meta http-equiv="refresh" content="5;url=<?php echo $redirect_url; ?>" />
    <?php
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
    <div id="pdf-preview" style="margin-top: 20px;">
        <!-- The PDF preview will be displayed here -->
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>

const fuenteBase64 = "data:font/ttf;base64,PUT_YOUR_BASE64_ENCODED_FONT_HERE";

    function toggleExtracurriculares(show) {
        document.getElementById('extracurriculares_detalle').classList.toggle('hidden', !show);
    }

    async function generarPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        var nombre_usuario = "<?php echo $nombre_usuario; ?>";
        var categoria = "<?php echo $categoria; ?>";
        var subcategoria = "<?php echo $subcategoria; ?>";
        var correo_usuario = "<?php echo $correo_usuario; ?>";
        var turno = "<?php echo $turno; ?>";

        // Título centrado y subrayado
        const titulo = `RUTINAS (${nombre_usuario})`;
        const pageWidth = doc.internal.pageSize.getWidth();
        const textWidth = doc.getTextWidth(titulo);
        const xPos = (pageWidth - textWidth) / 2;
        doc.text(titulo, xPos, 10);
        doc.setLineWidth(0.5);
        doc.line(xPos, 12, xPos + textWidth, 12);

        // Espacio reducido entre el título y la información del usuario
        const yOffset = 10;  // ESPACIO ENTRE EL TITULO Y EL NOMBRE DEL USUARIO
        const lineHeight = 10; // DISTACIA ENTRE PALABRAS

        // Información del usuario con espacio reducido entre líneas
        let currentY = 10 + yOffset;
        doc.text(`NOMBRE: ${nombre_usuario}`, 10, currentY);
        currentY += lineHeight;
        doc.text(`CORREO: ${correo_usuario}`, 10, currentY);
        currentY += lineHeight;
        
        // Función para dibujar cuadrados
        function drawSquare(doc, x, y, size) {
            doc.rect(x, y, size, size);
        }

        // Secciones de la rutina con cuadrados
        const sectionYOffset = currentY + 20;
        const squareSize = 5; // Tamaño del cuadrado
        const squareSpacing = 5; // Espacio más pequeño entre cuadrados

        // Línea separadora arriba de "Mañana"
        const lineYBeforeMañana = sectionYOffset - 10;  // Justo antes de la sección "Mañana"
        doc.setLineWidth(0.5);
        doc.line(10, lineYBeforeMañana, pageWidth - 10, lineYBeforeMañana);  // Línea horizontal arriba de Mañana

        // Mañana
        const mañanaY = sectionYOffset;
        doc.text('MAÑANA (6:00 - 12:00)', 10, mañanaY);
        drawSquare(doc, 10, mañanaY + 5, squareSize); // Primer cuadrado
        drawSquare(doc, 10, mañanaY + 5 + squareSize + squareSpacing, squareSize); // Segundo cuadrado
        drawSquare(doc, 10, mañanaY + 5 + (2 * (squareSize + squareSpacing)), squareSize); // Tercer cuadrado
        drawSquare(doc, 10, mañanaY + 5 + (3 * (squareSize + squareSpacing)), squareSize); // Cuarto cuadrado
        drawSquare(doc, 10, mañanaY + 5 + (4 * (squareSize + squareSpacing)), squareSize); // Quinto cuadrado
        
        // Línea separadora entre Mañana y Tarde
        const lineYAfterMañana = mañanaY + 5 + (5 * (squareSize + squareSpacing)) + 5;  // Después de los cuadrados de la mañana
        doc.setLineWidth(0.5);
        doc.line(10, lineYAfterMañana, pageWidth - 10, lineYAfterMañana);  // Línea horizontal

        // Tarde
        const tardeY = lineYAfterMañana + 10;  // Añadir un pequeño margen
        doc.text('TARDE (12:00 - 19:00)', 10, tardeY);
        drawSquare(doc, 10, tardeY + 5, squareSize); // Primer cuadrado
        drawSquare(doc, 10, tardeY + 5 + squareSize + squareSpacing, squareSize); // Segundo cuadrado
        drawSquare(doc, 10, tardeY + 5 + (2 * (squareSize + squareSpacing)), squareSize); // Tercer cuadrado
        drawSquare(doc, 10, tardeY + 5 + (3 * (squareSize + squareSpacing)), squareSize); // Cuarto cuadrado
        drawSquare(doc, 10, tardeY + 5 + (4 * (squareSize + squareSpacing)), squareSize); // Quinto cuadrado

        // Línea separadora entre Tarde y Noche
        const lineYAfterTarde = tardeY + 5 + (5 * (squareSize + squareSpacing)) + 5;  // Después de los cuadrados de la tarde
        doc.line(10, lineYAfterTarde, pageWidth - 10, lineYAfterTarde);  // Línea horizontal

        // Noche
        const nocheY = lineYAfterTarde + 10;  // Añadir un pequeño margen
        doc.text('NOCHE (19:00 - 24:00)', 10, nocheY);
        drawSquare(doc, 10, nocheY + 5, squareSize); // Primer cuadrado
        drawSquare(doc, 10, nocheY + 5 + squareSize + squareSpacing, squareSize); // Segundo cuadrado
        drawSquare(doc, 10, nocheY + 5 + (2 * (squareSize + squareSpacing)), squareSize); // Tercer cuadrado
        drawSquare(doc, 10, nocheY + 5 + (3 * (squareSize + squareSpacing)), squareSize); // Cuarto cuadrado
        drawSquare(doc, 10, nocheY + 5 + (4 * (squareSize + squareSpacing)), squareSize); // Quinto cuadrado

        // Variables de control de altura
        let currentYOffsetMorning = mañanaY + 10;
        let currentYOffsetAfternoon = tardeY + 10;
        let currentYOffsetNight = nocheY + 10;

        // Obtener las tareas
        <?php
        $consultaTareas = "SELECT * FROM rutinaview WHERE nombre_usuario = '$nombre_usuario'";
        $result = mysqli_query($conexion, $consultaTareas);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($fila = mysqli_fetch_assoc($result)) {
                $categoria_tarea = $fila['nombre_categoria'];
                $subcategoria_tarea = $fila['nombre_subcategoria'];
                $turno_tarea = $fila['turno'];
                ?>
                if (`<?php echo $turno_tarea; ?>` === 'Mañana') {
                    doc.text(`Tarea: <?php echo $categoria_tarea; ?> --> (<?php echo $subcategoria_tarea; ?>)`, 20, currentYOffsetMorning);
                    currentYOffsetMorning += lineHeight;
                } else if (`<?php echo $turno_tarea; ?>` === 'Tarde') {
                    doc.text(`Tarea: <?php echo $categoria_tarea; ?> --> (<?php echo $subcategoria_tarea; ?>)`, 20, currentYOffsetAfternoon);
                    currentYOffsetAfternoon += lineHeight;
                } else if (`<?php echo $turno_tarea; ?>` === 'Noche') {
                    doc.text(`Tarea: <?php echo $categoria_tarea; ?> --> (<?php echo $subcategoria_tarea; ?>)`, 20, currentYOffsetNight);
                    currentYOffsetNight += lineHeight;
                }
                <?php
            }
        }
        ?>

        // Obtener el blob del PDF
        const pdfBlob = doc.output('blob');

        // Crear una URL para el blob y mostrarla en un iframe
        const pdfUrl = URL.createObjectURL(pdfBlob);
        const iframe = document.createElement('iframe');
        iframe.style.width = '100%';
        iframe.style.height = '500px';
        iframe.src = pdfUrl;

        // Limpiar cualquier previsualización anterior y agregar la nueva
        const pdfPreviewDiv = document.getElementById('pdf-preview');
        pdfPreviewDiv.innerHTML = '';
        pdfPreviewDiv.appendChild(iframe);
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

    
    //CONSULTA PARA SUMARLE LA EXP y NIVEL AL USUARIO POR HABER 'COMPLETADO' LA TAREA
    if($estado == 'PENDIENTE'){
        $AsignarEXPyNIVEL = "UPDATE usuarios SET EXP=usuarios.EXP+50,NIVEL=usuarios.EXP/100 WHERE nombre_usuario='$nombre_usuario';";
        $AsignarEXPyNIVEL = mysqli_query($conexion,$AsignarEXPyNIVEL);
        echo "<br><br>";
        echo "CONECTADO PAPU";
        
        $setCompletada = "UPDATE rutinaview SET estado = 'COMPLETADA' WHERE id_tarea = $id_tarea;";
        $setCompletada = mysqli_query($conexion,$setCompletada);
        
        echo $nombre_usuario;
        echo $correo_usuario;

        $crearTareaphpSESION = 1;

        $redirect_url = "interfazUsuario.php?paso=0&iniciosesionConsulta=1&consultalistaTareas=0&nombre_usuario=" . urlencode($nombre_usuario) . "&correo_usuario=" . urlencode($correo_usuario). "&crearTareaphpSESION=" . urlencode($crearTareaphpSESION) . "#redirigirSesion";
        ?>
        <audio src="../audio/experiencia.mp3" autoplay></audio>
        <meta http-equiv="refresh" content="2;url=<?php echo $redirect_url; ?>" />

        <?php
        
    }else {
        $crearTareaphpSESION = 1;

        $redirect_url = "interfazUsuario.php?paso=0&iniciosesionConsulta=1&consultalistaTareas=0&nombre_usuario=" . urlencode($nombre_usuario) . "&correo_usuario=" . urlencode($correo_usuario). "&crearTareaphpSESION=" . urlencode($crearTareaphpSESION) . "#redirigirSesion";

        ?>
            <h3 style="color: red">
                ¡TE ATRAPAMOS SANDIJUELA ESCURRIDIZA!<br>
                No puedes conseguir más experiencia de esta tarea. 
                <meta http-equiv="refresh" content="2;url=<?php echo $redirect_url; ?>" />
            </h3>
        <?php
    }
        

}
?>
</body>
</html>
