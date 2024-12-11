<?php
session_start();
include("conexion.php");

// Determinar el paso actual
$paso = isset($_GET['paso']) ? intval($_GET['paso']) : 0;

// Paso 0: Procesar POST y establecer sesión
if ($paso === 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombre_usuario'], $_POST['correo_usuario'])) {
        $_SESSION['usuario'] = [
            'nombre' => $_POST['nombre_usuario'],
            'correo' => $_POST['correo_usuario']
        ];
    } else {
        die("Error: Los datos del usuario no se enviaron correctamente.");
    }
}

// Verificar que los datos de usuario estén en la sesión
if (!isset($_SESSION['usuario'])) {
    die("Error: Los datos del usuario no están disponibles.");
}

// Recuperar datos de usuario
$nombre_usuario = $_SESSION['usuario']['nombre'];
$correo_usuario = $_SESSION['usuario']['correo'];

// Procesar lógica según el paso
switch ($paso) {
    case 0:
        // Obtener 'id_usuario' solo para el uso actual
        $consultaId_Usuario = "SELECT id_usuario FROM usuarios WHERE nombre_usuario='$nombre_usuario';";
        $resultadoId_Usuario = mysqli_query($conexion, $consultaId_Usuario);

        if (!$resultadoId_Usuario || mysqli_num_rows($resultadoId_Usuario) === 0) {
            die("Error: No se encontró el usuario en la base de datos.");
        }

        $id_usuario = mysqli_fetch_row($resultadoId_Usuario)[0];

        // Guardar id_usuario en la sesión para su uso posterior
        $_SESSION['usuario']['id_usuario'] = $id_usuario;

        // Obtener nombres de las categorías
        $consultaCategorias = "SELECT nombre_categoria FROM categorias;";
        $resultadoCategorias = mysqli_query($conexion, $consultaCategorias);

        if (!$resultadoCategorias) {
            die("Error en la consulta de categorías: " . mysqli_error($conexion));
        }

        // Renderizar formulario
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/styles.css">
            <title>Crear Tarea: "<?php echo htmlspecialchars($nombre_usuario); ?>"</title>
        </head>
        <body>
        <form action="gestionTareas.php?paso=1" method="post">
            <h1>Formulario de Gestión de Tareas: 0/4</h1>
            <fieldset class="datosTarea">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <?php
                    while ($fila = mysqli_fetch_assoc($resultadoCategorias)) {
                        echo "<option>" . htmlspecialchars($fila['nombre_categoria']) . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="comun2" value="Seleccionar">
                <input type="reset" name="Limpiar" value="LIMPIAR">
            </fieldset>
        </form>
        </body>
        </html>
        <?php
    break;

    case 1:
        
        $_SESSION['categoria'] = $_POST['categoria'];
    
        // Recuperar datos necesarios desde la sesión
        $categoria = $_SESSION['categoria'];
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        $nombre_usuario = $_SESSION['usuario']['nombre'];
        $correo_usuario = $_SESSION['usuario']['correo'];
    
        // Obtener las subcategorías para la categoría seleccionada
        $consultaOptionSubcategorias = "SELECT nombre_subcategoria FROM subcategorias, categorias WHERE nombre_categoria='$categoria' AND subcategorias.id_categoria = categorias.id_categoria;";
        $resultadoSubcategorias = mysqli_query($conexion, $consultaOptionSubcategorias);
    
        if (!$resultadoSubcategorias) {
            die("Error en la consulta de subcategorías: " . mysqli_error($conexion));
        }
    
        // Renderizar formulario para seleccionar subcategoría
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/styles.css">
            <title>Crear Tarea: "<?php echo htmlspecialchars($nombre_usuario); ?>"</title>
        </head>
        <body>
        <form action="gestionTareas.php?paso=2" method="post">
            <h1>Formulario de Gestión de Tareas: 1/4</h1>
            <fieldset class="datosTarea">
                <label for="categoria">Categoría: <?php echo htmlspecialchars($categoria); ?></label>
                <br><br>
                <label for="subcategoria">Subcategoría:</label>
                <select id="subcategoria" name="subcategoria" required>
                    <?php
                    while ($fila = mysqli_fetch_assoc($resultadoSubcategorias)) {
                        echo "<option>" . htmlspecialchars($fila['nombre_subcategoria']) . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" name="comun2" value="Seleccionar">
                <input type="reset" name="Limpiar" value="LIMPIAR">
            </fieldset>
        </form>
        </body>
        </html>
        <?php
    break;

    case 2:
        
        $_SESSION['subcategoria'] = $_POST['subcategoria'];

        // Recuperar datos necesarios desde la sesión
        $categoria = $_SESSION['categoria'];
        $subcategoria = $_SESSION['subcategoria'];
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        $nombre_usuario = $_SESSION['usuario']['nombre'];
        $correo_usuario = $_SESSION['usuario']['correo'];
    
        // Renderizar formulario para seleccionar turno
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/styles.css">
            <title>Crear Tarea: "<?php echo htmlspecialchars($nombre_usuario); ?>"</title>
        </head>
        <body>
        <form action="gestionTareas.php?paso=3" method="post">
            <h1>Formulario de Gestión de Tareas: 2/4</h1>
            <fieldset class="datosTarea">
                <label for="categoria">Categoría: <?php echo htmlspecialchars($categoria); ?></label>
                <br><br>
                <label for="subcategoria">Subcategoría: <?php echo htmlspecialchars($subcategoria); ?></label>
                <br><br>
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
        </form>
        
        </body>
        </html>
        <?php
    
    break;
    
    case 3:

        $_SESSION['turno'] = $_POST['turno'];

        // Paso 3: Mostramos la información de la tarea a crear
        $categoria = $_SESSION['categoria'];  // Ya guardado en la sesión en pasos anteriores
        $subcategoria = $_SESSION['subcategoria'];  // Ya guardado en la sesión en pasos anteriores
        $turno = $_SESSION['turno'];  // Ya guardado en la sesión en pasos anteriores
        $id_usuario = $_SESSION['usuario']['id_usuario'];

        ?>
        <form action="gestionTareas.php?paso=4" method="post">
            <h1>Formulario de Gestión de Tareas: 3/4</h1>
            <fieldset class="datosTarea">
                <label for="categoria">Categoría: <?php echo $categoria; ?></label>
                <br><br>
                <label for="subcategoria">Subcategoría: <?php echo $subcategoria; ?></label>
                <br><br>
                <label for="turno">Turno: <?php echo $turno; ?></label>
                <br><br>
                <label for="id_usuario">Id Usuario: <?php echo $id_usuario; ?></label>
            </fieldset>
            <br>
            <input type="submit" name="tarea" value="Subir tarea">
        </form>
        <?php

    break;

    case 4:

        $categoria = $_SESSION['categoria'];  // Ya guardado en la sesión en pasos anteriores
        $subcategoria = $_SESSION['subcategoria'];  // Ya guardado en la sesión en pasos anteriores
        $turno = $_SESSION['turno'];  // Ya guardado en la sesión en pasos anteriores
        $id_usuario = $_SESSION['usuario']['id_usuario'];

    
        //Consulto por el 'id_subcategoria' para lograr la referencia entre las tablas
        $id_subcategoria = "SELECT id_subcategoria FROM subcategorias WHERE nombre_subcategoria='$subcategoria';";
        $id_subcategoria = mysqli_query($conexion, $id_subcategoria);
        $id_subcategoria = mysqli_fetch_row($id_subcategoria);
        $id_subcategoria = $id_subcategoria[0];
    
        //Inserto los valores que se guardaron en las anteriores variables
        $insertTarea = "INSERT INTO tareas(id_tarea, id_usuario, id_subcategoria, turno, estado) VALUES ('','$id_usuario','$id_subcategoria','$turno','PENDIENTE');";
    
        //2° Ejecuto las consultas en la BD y sobreescribo lo obtenido en esas mismas variables
        $insertTarea = mysqli_query($conexion, $insertTarea);
    
        // Guardar los datos en la sesión
        $_SESSION['nombre_usuario'] = $nombre_usuario; // Debe estar definido
        $_SESSION['correo_usuario'] = $correo_usuario; // Debe estar definido

        header("Location: interfazUsuario.php?paso=0");

    break;

    case 5:

        $categoria = $_SESSION['categoria'];
        $subcategoria = $_SESSION['subcategoria'];
        $turno = $_SESSION['turno'];
        $nombre_usuario = $_SESSION['usuario']['nombre'];
        $correo_usuario = $_SESSION['usuario']['correo'];
        ?>

        <div id="pdf-preview" style="margin-top: 20px;">
        </div>

         <!-- Incluye la librería html2pdf -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

        <script>
            function toggleExtracurriculares(show) {
                document.getElementById('extracurriculares_detalle').classList.toggle('hidden', !show);
            }

            async function generarPDF() {
                var nombre_usuario = "<?php echo $nombre_usuario; ?>";
                var categoria = "<?php echo $categoria; ?>";
                var subcategoria = "<?php echo $subcategoria; ?>";
                var correo_usuario = "<?php echo $correo_usuario; ?>";
                var turno = "<?php echo $turno; ?>";

                // Crear contenido HTML para el PDF
                var contenidoHTML = `
                    <div style="text-align: center;">
                        <h1>RUTINAS (${nombre_usuario})</h1>
                        <hr>
                        <p><strong>NOMBRE:</strong> ${nombre_usuario}</p>
                        <p><strong>CORREO:</strong> ${correo_usuario}</p>
                    </div>
                    <div style="margin-top: 20px;">
                        <h3>MAÑANA (6:00 - 12:00)</h3>
                        <div style="display: flex; flex-direction: column;">
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                        </div>
                        <hr>
                        <h3>TARDE (12:00 - 19:00)</h3>
                        <div style="display: flex; flex-direction: column;">
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                        </div>
                        <hr>
                        <h3>NOCHE (19:00 - 00:00)</h3>
                        <div style="display: flex; flex-direction: column;">
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                            <div style="height: 5px; width: 5px; border: 1px solid black; margin-bottom: 5px;"></div>
                        </div>
                    </div>
                `;

                // Reemplaza las tareas dinámicamente
                <?php
                $consultaTareas = "SELECT * FROM rutinaview WHERE nombre_usuario = '$nombre_usuario'";
                $result = mysqli_query($conexion, $consultaTareas);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($fila = mysqli_fetch_assoc($result)) {
                        $categoria_tarea = $fila['nombre_categoria'];
                        $subcategoria_tarea = $fila['nombre_subcategoria'];
                        $turno_tarea = $fila['turno'];
                        ?>
                        contenidoHTML += `<p><strong>Tarea:</strong> <?php echo $categoria_tarea; ?> --> (<?php echo $subcategoria_tarea; ?>)</p>`;
                        <?php
                    }
                }
                ?>

                // Crear un blob del contenido HTML
                const element = document.createElement('div');
                element.innerHTML = contenidoHTML;

                // Opciones para html2pdf
                const options = {
                    filename: `${nombre_usuario} - rutina.pdf`,
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };

                // Generar PDF
                html2pdf().from(element).set(options).save();
            }

            window.onload = function () {
                generarPDF(); // Llamar a la función al cargar la página
            };
        </script>


    <?php

    break;

    case 6:

        if (isset($_GET['paso']) && $_GET['paso'] == 6) {
            $id_tarea = htmlspecialchars($_POST['id_tarea']);
            $estado_actual = "SELECT estado FROM rutinaview WHERE id_tarea = $id_tarea";
            $resultado_estado = mysqli_query($conexion, $estado_actual);
            $fila_estado = mysqli_fetch_assoc($resultado_estado);
            $estado = $fila_estado['estado'];
        
            if($estado == 'PENDIENTE'){
                $AsignarEXPyNIVEL = "UPDATE usuarios SET EXP=usuarios.EXP+50,NIVEL=usuarios.EXP/100 WHERE nombre_usuario='$nombre_usuario';";
                $AsignarEXPyNIVEL = mysqli_query($conexion,$AsignarEXPyNIVEL);
                echo "<br><br>";
                echo "CONECTADO PAPU";
                
                $setCompletada = "UPDATE rutinaview SET estado = 'COMPLETADA' WHERE id_tarea = $id_tarea;";
                $setCompletada = mysqli_query($conexion,$setCompletada);
        
                header("Location: interfazUsuario.php?paso=0&mensaje=Tarea completada con éxito!");

                ?>
                
                <audio src="../audio/experiencia.mp3" autoplay></audio>
                <meta http-equiv="refresh" content="2;url=<?php echo $redirect_url; ?>" />

                <?php
                
                exit();  
            } else {
                header("Location: interfazUsuario.php?paso=0&mensaje=No puedes completar esta tarea nuevamente.");
                exit();
            }
        }        

    break;
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

        $crearTareaphpSESSION = 1;

        $redirect_url = "interfazUsuario.php?paso=0&iniciosesionConsulta=1&consultalistaTareas=0&nombre_usuario=" . urlencode($nombre_usuario) . "&correo_usuario=" . urlencode($correo_usuario). "&crearTareaphpSESION=" . urlencode($crearTareaphpSESION);
        ?>
        <audio src="../audio/experiencia.mp3" autoplay></audio>
        <meta http-equiv="refresh" content="2;url=<?php echo $redirect_url; ?>" />

        <?php
        
    }else {
        $crearTareaphpSESSION = 1;

        $redirect_url = "interfazUsuario.php?paso=0&iniciosesionConsulta=1&consultalistaTareas=0&nombre_usuario=" . urlencode($nombre_usuario) . "&correo_usuario=" . urlencode($correo_usuario). "&crearTareaphpSESION=" . urlencode($crearTareaphpSESION);

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


        