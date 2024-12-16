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
        
        ?>
    <div class="display">
        <div class="centrar">
        <?php
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
                $nombre_usuario = $_SESSION['usuario']['nombre'];
            
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
                            
                        <?php
                            // Obtener la fecha actual en formato 'YYYY-MM-DD'
                            $fecha_actual = date(format: 'Y-m-d');
                        ?>

                        <label for="fecha">Fecha:
                            <input type="date" id="fecha" name="fecha" min="<?php echo $fecha_actual; ?>" required>
                        </label>

                        <script>
                            // Establecer la fecha por defecto en el campo de fecha
                            let fechaInput = document.getElementById('fecha');
                            let fechaHoy = '<?php echo $fecha_actual; ?>';
                            
                            // Asegurarse de que el valor predeterminado sea la fecha de hoy
                            fechaInput.value = fechaHoy;
                            
                            // Establecer la fecha mínima al día de hoy
                            fechaInput.setAttribute('min', fechaHoy);
                        </script>

                        <input type="submit" name="comun2" value="Seleccionar">
                        <input type="reset" name="Limpiar" value="LIMPIAR">
                    </fieldset>
                </form>
                
                </body>
                </html>
                <?php
            
            break;

            case 3:

                $_SESSION['fecha'] = $_POST['fecha'];
                // Recuperar datos necesarios desde la sesión
                $categoria = $_SESSION['categoria'];
                $subcategoria = $_SESSION['subcategoria'];
                $nombre_usuario = $_SESSION['usuario']['nombre'];
                $fecha = $_SESSION['fecha'];  // Ya guardado en la sesión en pasos anteriores

            
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
                <form action="gestionTareas.php?paso=4" method="post">
                    <h1>Formulario de Gestión de Tareas: 2/4</h1>
                    <fieldset class="datosTarea">
                        <label for="categoria">Categoría: <?php echo htmlspecialchars($categoria); ?></label>
                        <br><br>
                        <label for="subcategoria">Subcategoría: <?php echo htmlspecialchars($subcategoria); ?></label>
                        <br><br>
                        <label for="fecha">Fecha: <?php echo htmlspecialchars($fecha); ?></label>
                        <br><br>
                        <label for="turno">Turno:</label>
                        <select id="turno" name="turno" required>
                            <option value="Mañana">( 7 a 12 ) Mañana </option>
                            <option value="Tarde">(12 a 19) Tarde </option>
                            <option value="Noche">(19 a 00) Noche </option>
                        </select>
                        <input type="submit" name="comun2" value="Seleccionar">
                        <input type="reset" name="Limpiar" value="LIMPIAR">
                    </fieldset>
                </form>
                
                </body>
                </html>
                <?php
            
            break;
            
            case 4:

                $_SESSION['turno'] = $_POST['turno'];

                // Paso 3: Mostramos la información de la tarea a crear
                $categoria = $_SESSION['categoria'];  // Ya guardado en la sesión en pasos anteriores
                $subcategoria = $_SESSION['subcategoria'];  // Ya guardado en la sesión en pasos anteriores
                $fecha = $_SESSION['fecha'];  // Ya guardado en la sesión en pasos anteriores
                $turno = $_SESSION['turno'];  // Ya guardado en la sesión en pasos anteriores
                $id_usuario = $_SESSION['usuario']['id_usuario'];

                ?>
                <form action="gestionTareas.php?paso=5" method="post">
                    <h1>Formulario de Gestión de Tareas: 3/4</h1>
                    <fieldset class="datosTarea">
                        <label for="categoria">Categoría: <?php echo $categoria; ?></label>
                        <br><br>
                        <label for="subcategoria">Subcategoría: <?php echo $subcategoria; ?></label>
                        <br><br>
                        <label for="turno">Turno: <?php echo $turno; ?></label>
                        <br><br>
                        <label for="fecha">Fecha: <?php echo $fecha; ?></label>
                        <br><br>
                        <label for="id_usuario">Id Usuario: <?php echo $id_usuario; ?></label>
                    </fieldset>
                    <br>
                    <input type="submit" name="tarea" value="Subir tarea">
                </form>
                <?php

            break;

            case 5:

                $categoria = $_SESSION['categoria'];  // Ya guardado en la sesión en pasos anteriores
                $subcategoria = $_SESSION['subcategoria'];  // Ya guardado en la sesión en pasos anteriores
                $turno = $_SESSION['turno'];  // Ya guardado en la sesión en pasos anteriores
                $id_usuario = $_SESSION['usuario']['id_usuario'];
                $fecha = $_SESSION['fecha'];
            
                //Consulto por el 'id_subcategoria' para lograr la referencia entre las tablas
                $id_subcategoria = "SELECT id_subcategoria FROM subcategorias WHERE nombre_subcategoria='$subcategoria';";
                $id_subcategoria = mysqli_query($conexion, $id_subcategoria);
                $id_subcategoria = mysqli_fetch_row($id_subcategoria);
                $id_subcategoria = $id_subcategoria[0];
                
                //Inserto los valores que se guardaron en las anteriores variables
                $insertTarea = "INSERT INTO tareas(id_tarea, id_usuario, id_subcategoria, fecha, turno,estado) VALUES ('','$id_usuario','$id_subcategoria','$fecha','$turno','PENDIENTE');";
            
                //2° Ejecuto las consultas en la BD y sobreescribo lo obtenido en esas mismas variables
                $insertTarea = mysqli_query($conexion, $insertTarea);
            
                // Guardar los datos en la sesión
                $_SESSION['nombre_usuario'] = $nombre_usuario; // Debe estar definido
                $_SESSION['correo_usuario'] = $correo_usuario; // Debe estar definido

                header("Location: interfazUsuario.php");

            break;
            
            case 6:

                // Fetch user data from session
                $categoria = $_SESSION['categoria'];
                $subcategoria = $_SESSION['subcategoria'];
                $turno = $_SESSION['turno'];
                $nombre_usuario = $_SESSION['usuario']['nombre'];
                $correo_usuario = $_SESSION['usuario']['correo'];
                
                $consultaTareas = "SELECT * FROM rutinaview WHERE nombre_usuario = '$nombre_usuario'";
                $result = mysqli_query($conexion, $consultaTareas);
                $tareas = [
                    'mañana' => [],
                    'tarde' => [],
                    'noche' => []
                ];

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($fila = mysqli_fetch_assoc($result)) {
                        $turno_tarea = $fila['turno']; // Tarea turno
                        if (strpos($turno_tarea, 'Mañana') !== false) {
                            $tareas['mañana'][] = [
                                'categoria' => $fila['nombre_categoria'],
                                'subcategoria' => $fila['nombre_subcategoria'],
                                'fecha' => $fila['fecha']
                            ];
                        } elseif (strpos($turno_tarea, 'Tarde') !== false) {
                            $tareas['tarde'][] = [
                                'categoria' => $fila['nombre_categoria'],
                                'subcategoria' => $fila['nombre_subcategoria'],
                                'fecha' => $fila['fecha']
                            ];
                        } elseif (strpos($turno_tarea, 'Noche') !== false) {
                            $tareas['noche'][] = [
                                'categoria' => $fila['nombre_categoria'],
                                'subcategoria' => $fila['nombre_subcategoria'],
                                'fecha' => $fila['fecha']
                            ];
                        }
                    }
                }
                ?>

                <h1>Formulario de Gestión de Tareas: 4/4</h1>
                <button type="button" onclick="generarPDF()">Generar PDF</button>
                <!-- Cargar librería html2pdf -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>

                <script>
                function toggleExtracurriculares(show) {
                    document.getElementById('extracurriculares_detalle').classList.toggle('hidden', !show);
                }

                async function generarPDF() {
                    // Variables dinámicas PHP
                    var nombre_usuario = "<?php echo $nombre_usuario; ?>";
                    var categoria = "<?php echo $categoria; ?>";
                    var subcategoria = "<?php echo $subcategoria; ?>";
                    var correo_usuario = "<?php echo $correo_usuario; ?>";
                    var turno = "<?php echo $turno; ?>";

                    // Tasks fetched from PHP to JS
                    var tareas = <?php echo json_encode($tareas); ?>;
                    var tareasHTML = ``;

                    <?php
                    // Generar las tareas dinámicamente en PHP
                    $consultaTareas = "SELECT * FROM rutinaview WHERE nombre_usuario = '$nombre_usuario'";
                    $result = mysqli_query($conexion, $consultaTareas);
                    
                    ?>

                    // Contenido HTML completo para el PDF
                    var contenidoHTML = `
                        <style>
                            @import url('https://fonts.cdnfonts.com/css/cyberfall');
                            @import url('https://fonts.cdnfonts.com/css/iceberg');

                            body {
                                font-family: 'iceberg', sans-serif;
                                margin: 20px;
                                background-color: #f9f9f9;
                            }
                            h1, h2 {
                                font-family: 'cyberfall', sans-serif;
                                text-align: center;
                            }
                            .titulo {
                                text-align: center;
                                background-color: #ccc;
                                padding: 10px;
                                margin-bottom: 20px;
                            }
                            .rectangulo {
                                background-color: white;
                                border: 1px solid #000;
                                padding: 10px;
                                margin: 10px auto;
                                width: 90%;
                            }
                        </style>

                        <div>
                            <div class="titulo">
                                <h1>RUTINA</h1>
                                <p><strong>Usuario:</strong> ${nombre_usuario}</p>
                                <p><strong>Correo:</strong> ${correo_usuario}</p>
                            </div>

                            <div class="rectangulo">
                                <h2>MAÑANA (07:00 - 12:00)</h2>
                                ${generateTasksHTML(tareas['mañana'])}
                            </div>

                            <div class="rectangulo">
                                <h2>TARDE (12:00 - 19:00)</h2>
                                ${generateTasksHTML(tareas['tarde'])}
                            </div>

                            <div class="rectangulo">
                                <h2>NOCHE (19:00 - 00:00)</h2>
                                ${generateTasksHTML(tareas['noche'])}
                            </div>
                        </div>
                    `;
                    
                    // Function to generate tasks HTML based on the turno
                    function generateTasksHTML(tasks) {
                        let html = '';
                        tasks.forEach(function(tarea) {
                            html += `
                                <div style="font-family: 'iceberg';" class="sas1">
                                    <p><strong>Tarea:</strong> ${tarea.categoria} --> (${tarea.subcategoria} --> (${tarea.fecha})</p>
                                </div>
                            `;
                        });
                        return html;
                    }

                    // Generar y previsualizar el PDF usando html2pdf
                    const elemento = document.createElement('div');
                    elemento.innerHTML = contenidoHTML;

                    const nombreUsuario = "<?php echo htmlspecialchars($nombre_usuario, ENT_QUOTES, 'UTF-8'); ?>";
                    const opciones = {
                        margin: 10,
                        filename: `${nombreUsuario} - rutina.pdf`, // Nombre dinámico del archivo
                        html2canvas: { scale: 2 },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                    };

                    // Generar PDF y mostrar previsualización
                    html2pdf().from(elemento).set(opciones).outputPdf('datauristring').then(function (pdfUrl) {
                        const iframe = document.createElement('iframe');
                        iframe.style.width = '100%';
                        iframe.style.height = '500px';
                        iframe.src = pdfUrl;

                        // Mostrar la previsualización
                        const pdfPreviewDiv = document.getElementById('pdf-preview');
                        pdfPreviewDiv.innerHTML = '';
                        pdfPreviewDiv.appendChild(iframe);
                    });
                }
                
            </script>

            <!-- Contenedor para la previsualización del PDF -->
            <div id="pdf-preview" style="margin-top: 20px; padding: 10px; width: 1000px;"></div>

            <?php
            break;
            

            case 7:

                $id_tarea = $_POST['id_tarea'];

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
                
                    header("Location: interfazUsuario.php?&mensaje=Tarea completada con éxito!");

                    
                    // Marca la tarea como completada en la sesión
                    $_SESSION['tareaCompletada'] = true;
                    ?>

                    <?php
                    
                    header("Location: interfazUsuario.php");
            
                } else {
                    ?>

                    <video autoplay loop id="myVideo">
                        <source src="../video/TEGANE.mp4" type="video/mp4">
                    </video>
                    
                    <div class="content">
                        <h1>¡MALDITA RATA ESCURRIDIZA!</h1>
                        <p><b>No puedes conseguir EXPERIENCIA de una tarea que ya tienes completa</b></p>
                    </div>

                    <meta http-equiv="refresh" content="9.5;url=http://localhost/Sisifo/php/interfazUsuario.php" />
                    

                    <?php
                }      

            break;
            

            case 8:

                $id_tarea = $_POST['id_tarea'];
                 
                $eliminarTarea = "DELETE FROM tareas WHERE id_tarea = $id_tarea;";
                $eliminarTarea = mysqli_query($conexion,$eliminarTarea);
                echo "<br><br>";
                echo "CONECTADO PAPU";
                        
                header("Location: interfazUsuario.php?&mensaje=Tarea eliminada con éxito!");

                        
                // Marca la tarea como eliminada en la sesión
                $_SESSION['tareaEliminada'] = true;
                    
                    
                header("Location: interfazUsuario.php");          

            break;

            case 9:               
                $fotoNueva = mysqli_real_escape_string($conexion, $_GET['fotoNueva']);

                $cambiarFoto = "UPDATE usuarios SET img='$fotoNueva' WHERE nombre_usuario= '$nombre_usuario';";
                $cambiarFoto = mysqli_query($conexion,$cambiarFoto);
                echo "<br><br>";
                echo "CONECTADO PAPU";
                    
                header("Location: interfazUsuario.php");

            break;

            case 10:
                // Ruta del archivo que deseas descargar
                $rutaArchivo = "../arduino/dependencias_arduino.rar"; // Ajusta esta ruta según la ubicación real del archivo
                
                // Verifica si el archivo existe
                if (file_exists($rutaArchivo)) {
                    // Configurar encabezados para la descarga
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($rutaArchivo) . '"');
                    header('Content-Length: ' . filesize($rutaArchivo));
                    header('Pragma: public');
            
                    // Leer el archivo y enviarlo como respuesta
                    readfile($rutaArchivo);
                    exit;
                } else {
                    // Manejar el caso en que el archivo no existe
                    ?>
                    <script>
                        // Mostrar un alert en el navegador
                        alert("El archivo no existe o no está disponible. Reinicia la página o vuelve a intentar más tarde. ¡Lamentamos los problemos!");
                        
                        // Redirigir al usuario después de mostrar el mensaje
                        window.location.href = "interfazUsuario.php";
                    </script>
                    <?php
                }
                
            
                break;
            
        }
        ?>
        </div>
    </div>
</body>
</html>


        