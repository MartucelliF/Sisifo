<?php
ob_start();
session_start();

include ("conexion.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="icon" type="image/jpg" href="../img/icon.png"/>

<?php        
        //CONTROL DE SESIONES
        //---------------------------------------------------------------------------------------------------     
            /* Las declaro para evitar que muestre el error de que no están definidas, ya que, técnicamente, no se definen o no toman un valor lógico
            hasta que las trae de gestionTareas.php */
            $audioCompletado = false;
            $audioEliminado = false;
            $audioCreado = false;
            $sesionIniciada = false;

            // AUDIO AL INICIAR SESIÓN
            // Reproducir el sonido solo la primera vez
                if (isset($_SESSION['audio_played']) && $_SESSION['audio_played'] === true) {
                    $sesionIniciada = true;
                    unset($_SESSION['audio_played']); // Elimina la variable para evitar reproducción repetida
                }           
            
            // AUDIO AL CREAR TAREA
                if (isset($_SESSION['tareaCreada']) && $_SESSION['tareaCreada'] === true) {
                    $audioCreado = true;
                    unset($_SESSION['tareaCreada']); // Elimina la variable para evitar reproducción repetida
                }
            // -------------------------

            // AUDIO AL COMPLETAR TAREA
                if (isset($_SESSION['tareaCompletada']) && $_SESSION['tareaCompletada'] === true) {
                    $audioCompletado = true;
                    unset($_SESSION['tareaCompletada']); // Elimina la variable para evitar reproducción repetida
                }
            // ---------------------------
        
            // AUDIO AL ELIMINAR TAREA
                if (isset($_SESSION['tareaEliminada']) && $_SESSION['tareaEliminada'] === true) {
                    $audioEliminado = true;
                    unset($_SESSION['tareaEliminada']); // Elimina la variable para evitar reproducción repetida
                }
            // -------------------------
        // -----------------------------------------



            $sesionCerrada = false;
            // Procesar cierre de sesión
            if (isset($_GET['logout'])) {
                $sesionCerrada = true;
                $delete_usuario_actual = "DELETE FROM usuario_actual";
                $delete_usuario_actual = mysqli_query($conexion, $delete_usuario_actual);
                session_start();
                session_unset(); // Limpia todas las variables de la sesión
                session_destroy(); // Destruye la sesión
                setcookie(session_name(), '', time() - 3600, '/'); // Elimina la cookie de la sesión
                header("Location: ../index.php?sesionCerrada=1"); // Redirigir con indicador de sesión cerrada
                exit;
            }
            

            /*Los casos están hechos para las diferentes formas de tomar datos de cada sesión. 
            Para que no se sobreescriban los datos ni aparezcan errores 'undefined'*/
        //----------------------------------------------------------------------------------------------------
           
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
                        <meta http-equiv="Refresh" content="5; url='../index.php'" />

                        <h2 style="color: red; text-align: center;"> El correo electrónico "<i>
                                <?php echo $correo_usuario ?>
                            </i>" o la contraseña es incorrecta.<br>
                            Intente con otras credenciales.</h2>

                        <!--Entonces no se inserta el nuevo suario y lo devuelve al registro-->
                        
                        <audio src="../audio/sad.mp3" autoplay></audio>
                    <?php
                    //-------------------------------------------------------------------------------------------
                }
                else{
            
    ?>
    <title>SÍSIFO: <?php echo htmlspecialchars($nombre_usuario); ?></title>
        <link rel="stylesheet" href="../css/styles.css">
    </head>
    <body>          
                    <?php
                    if ($sesionCerrada){
                    ?>
                        <audio src="../audio/cerrarSesion.mp3" autoplay></audio>
                    <?php   
                    }
                    ?>
                    <?php
                    if ($sesionIniciada){
                    ?>
                        <audio src="../audio/iniciarSesion.mp3" autoplay></audio>
                        <?php
                            $consulta_usuario_actual = "SELECT * FROM usuario_actual WHERE nombre_usuario='$nombre_usuario';";
                            $consulta_usuario_actual = mysqli_query($conexion, $consulta_usuario_actual);
                            $consulta_usuario_actual = mysqli_fetch_row($consulta_usuario_actual);
                            $consulta_usuario_actual = $consulta_usuario_actual[0];
                            
                            if($consulta_usuario_actual == NULL){
                                $insert_usuario_actual = "INSERT INTO `usuario_actual`(`nombre_usuario`) VALUES ('$nombre_usuario')";
                                $insert_usuario_actual = mysqli_query($conexion, $insert_usuario_actual);
                            }else{
                                $delete_usuario_actual = "DELETE FROM usuario_actual";
                                $delete_usuario_actual = mysqli_query($conexion, $delete_usuario_actual);
                                
                                $insert_usuario_actual = "INSERT INTO `usuario_actual`(`nombre_usuario`) VALUES ('$nombre_usuario')";
                                $insert_usuario_actual = mysqli_query($conexion, $insert_usuario_actual);
                            }
                                
                        ?>
                    <?php 
                    }
                    if ($audioCreado){
                        ?>
                            <audio src="../audio/crearTarea.mp3" autoplay></audio>
                    <?php 
                        }
                    if ($audioCompletado){ ?>
                        <audio src="../audio/experiencia.mp3" autoplay></audio>
                    <?php 
                    } ?>

                    <?php 
                    if ($audioEliminado){ ?>
                        <audio src="../audio/TROLLING.mp3" autoplay></audio>
                    <?php 
                    } 

                    ?>
                    
                    <div class="display">
                        <id class="info">

                        <?php
                            // Establecer la zona horaria explícita
                            date_default_timezone_set('America/Argentina/Buenos_Aires'); // Cambia según tu región
                            
                            // PHP: Obtener la fecha actual del servidor en la zona horaria correcta
                            $fecha_actual = date('Y-m-d');
                            
                            // Determinar el turno inicial según la hora actual ajustada
                            $hora_actual = date('H:i:s');
                            
                            ?>
                            
                            <div class="horaEscondida">
                                <p>FECHA ACTUAL: <?php echo $fecha_actual; ?></p>
                                <span id="hora"></span>
                            </div>
                            
                            <?php
                            
                            $turno_actual="";
                            $turno_Maniana=false;
                            $turno_ManianaInicio = '07:00:00';
                            $turno_ManianaFin = '12:00:00';
                            if(($hora_actual>=$turno_ManianaInicio)&&($hora_actual<=$turno_ManianaFin)){
                                $turno_Maniana=true;
                            }

                            $turno_Tarde=false;
                            $turno_TardeInicio = '12:00:00';
                            $turno_TardeFin = '19:00:00';
                            if(($hora_actual>=$turno_TardeInicio)&&($hora_actual<=$turno_TardeFin)){
                                $turno_Tarde = true;
                            }

                            $turno_Noche=false;
                            $turno_NocheInicio = '19:00:00';
                            $turno_NocheFin = '23:00:00';
                            if(($hora_actual>=$turno_NocheInicio)&&($hora_actual<=$turno_NocheFin)){
                                $turno_Noche = true;
                            }

                            $turno_Descansar = false;
                            if(($hora_actual>$turno_NocheFin)||($hora_actual<$turno_ManianaInicio)){
                                $turno_Descansar = true;
                            }
                            ?>
                            
                            <script>
                            // Función para formatear y mostrar la hora actual
                                function actualizarHora() {
                                    const ahora = new Date();
                                    const horas = ahora.getHours().toString().padStart(2, '0');
                                    const minutos = ahora.getMinutes().toString().padStart(2, '0');
                                    const segundos = ahora.getSeconds().toString().padStart(2, '0');
                                    const horaFormateada = `${horas}:${minutos}:${segundos}`;

                                    document.getElementById('hora').innerText = horaFormateada;

                                    if (horaFormateada === "<?php echo $turno_ManianaFin?>") {
                                        alert("¡Es hora del cambio de turno! MAÑANA --> TARDE");
                                        location.reload(); // Refrescar la página
                                    }
                                    if (horaFormateada === "<?php echo $turno_TardeFin?>") {
                                        alert("¡Es hora del cambio de turno! TARDE --> NOCHE");
                                        location.reload(); // Refrescar la página
                                    }
                                    if (horaFormateada === "<?php echo $turno_NocheFin?>") {
                                        alert("¡Es hora del cambio de turno! A DESCANSAR");
                                        location.reload(); // Refrescar la página
                                    }
                                    
                                }

                                // Actualizar cada segundo
                                window.onload = function () {
                                    actualizarHora();
                                    setInterval(actualizarHora, 1000);
                                };
                            </script>
                            
                            <h1 id="bienvenida"> ¡Buenas, <?php echo $nombre_usuario ?>!</h1>
                            <br>
                            <div class="reloj">
                                <?php
                                if($turno_Maniana==true){
                                    ?>
                                    <h1>Mañana</h1> 
                                    <img src="../img/reloj/1.png" alt="" srcset="" width="125px" height="125px">               
                                    <?php
                                }
                                else if($turno_Tarde==true){
                                    ?>
                                    <h1>Tarde</h1> 
                                    <img src="../img/reloj/2.png" alt="" srcset="" width="125px" height="125px">               
                                    <?php
                                }
                                else if($turno_Noche==true){
                                    ?>
                                    <h1>Noche</h1> 
                                    <img src="../img/reloj/3.png" alt="" srcset="" width="125px" height="125px">       
                                    <?php
                                }else if($turno_Descansar = true){
                                    ?>
                                    <h1>A descansar un poco...</h1> 
                                    <img src="../img/reloj/0.png" alt="" srcset="" width="125px" height="125px">               
                                    <?php
                                }
                                ?>
                            </div>
                            <?php

                            //CONSULTA PARA OBTENER SU 'EXP' Y 'NIVEL'
                            $consultaEXP = "SELECT EXP FROM usuarios WHERE nombre_usuario='$nombre_usuario';";
                            $consultaEXP = mysqli_query($conexion, $consultaEXP);
                            $consultaEXP = mysqli_fetch_row($consultaEXP);
                            $EXP_usuario = $consultaEXP[0];

                            $consultaNIVEL = "SELECT NIVEL FROM usuarios WHERE nombre_usuario='$nombre_usuario';";
                            $consultaNIVEL = mysqli_query($conexion, $consultaNIVEL);
                            $consultaNIVEL = mysqli_fetch_row($consultaNIVEL);
                            $NIVEL_usuario = $consultaNIVEL[0];

                            ?>
                            <div id="NIVELEXP">
                                    <?php echo $NIVEL_usuario?>
                            </div>

                            <div>
                                <?php
                                    if($EXP_usuario==0){
                                        ?>
                                            <img src="../img/EXP/0.jpg" alt="barra de exp" width=100% height=100%>
                                        <?php
                                    }
                                    if($EXP_usuario==1){
                                        ?>
                                            <img src="../img/EXP/1.jpg" alt="barra de exp" width=100% height=100%>
                                        <?php
                                    }if($EXP_usuario==2){
                                        ?>
                                            <img src="../img/EXP/2.jpg" alt="barra de exp" width=100% height=100%>
                                        <?php
                                    }if($EXP_usuario==3){
                                        ?>
                                            <img src="../img/EXP/3.jpg" alt="barra de exp" width=100% height=100%>
                                        <?php
                                    }if($EXP_usuario==4){
                                        ?>
                                            <img src="../img/EXP/4.jpg" alt="barra de exp" width=100% height=100%>
                                        <?php
                                    }if($EXP_usuario==5){
                                        ?>
                                            <img src="../img/EXP/5.jpg" alt="barra de exp" width=100% height=100%>
                                        <?php
                                    }if($EXP_usuario==6){
                                        ?>
                                            <img src="../img/EXP/6.jpg" alt="barra de exp" width=100% height=100%>
                                        <?php
                                    }if($EXP_usuario==7){
                                        ?>
                                            <img src="../img/EXP/7.jpg" alt="barra de exp" width=100% height=100%>
                                        <?php
                                    }if($EXP_usuario==8){
                                        ?>
                                            <img src="../img/EXP/8.jpg" alt="barra de exp"width=100% height=100%>
                                        <?php
                                    }if($EXP_usuario==9){
                                        ?>
                                            <img src="../img/EXP/9.jpg" alt="barra de exp"width=100% height=100%>
                                        <?php
                                    }
                                ?>
                            </div>
                            <br>
                            <?php

                            //CONSULTA PARA OBTENER SU 'IMG'DE PERFIL
                            $img = "SELECT img FROM usuarios WHERE nombre_usuario='$nombre_usuario';";
                            $img = mysqli_query($conexion, $img);
                            $img = mysqli_fetch_row($img);
                            $img = $img[0];

                            if ($img=="") {
                                $img = "../img/default.png";
                            } else if(empty($img)) {
                                $img = "../img/default.png";
                            }

                            //CONSULTA PARA OBTENER LAS TAREAS DE ESE USUARIO
                            $consultalistaTareas = "SELECT * FROM rutinaview WHERE nombre_usuario='$nombre_usuario'";
                            $consultalistaTareas = mysqli_query($conexion, $consultalistaTareas);
                            
                            $tieneTareas;
                            
                            if (mysqli_num_rows($consultalistaTareas)==0) { 
                                $tieneTareas=false;
                            ?>
                               <h3>NO TIENES TAREAS REGISTRADAS</h3>
                            <?php
                            }else{
                                $tieneTareas=true;
                            }
                            ?>
                            <form action="gestionTareas.php" method="post">
                                <button type="submit" class="contboton"><img src="../img/botonAgregar.png" alt="botonAgregar" width="250px" height="40px"></button>
                                <input type="hidden" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                                <input type="hidden" name="correo_usuario" value="<?php echo $correo_usuario; ?>">
                            </form>

                            
                            <div class="ImgYcerrarSesion">
                                <button id="cambiarFotoBtn"><img src="<?php echo $img ?>" alt="" width="100px" height="100px" id="fotoPerfil"></button></img>
                               
                                <br>
                                <form action="" method="get" style="display:inline;">
                                    <button type="submit" name="logout" value="1" class="contboton"><img src="../img/botonCerrarSesion.png" alt="botonCerrarSesion" width="250px" height="40px"></button>
                                </form>                
                            </div>
                        </div>
                    </div>
                    
                    <div class="contenedorPrincipal">
                        <div class="left">
                            <!--1er columna del grid-->
                            <br>
                            
                                <a href="https://drive.google.com/drive/folders/1--6IlJjOVjoH4Y637lJS0tbm1nkVxl54?usp=sharing" target="_blank" >

                                    <button id="esconderBoton" type="submit">
                                        <img src="../img/arduino.png" alt="descargarArduino" width="150px" height="150px">
                                    </button>
                                </a
                                    <audio id="audio" src="../audio/click.mp3"></audio>


                            <br>
                            <br>
                            <form action="gestionTareas.php?paso=6" method="post">
                                <button id="esconderBoton">
                                    <img src="../img/pdf.png" alt="descargarPDF" width="100px" height="100px">
                                </button>
                            </form>
                        </div>
                        
                        <div class="centrar">
                            <div class="centrar">
                                <?php
                                if($tieneTareas==true){
                                ?>
                                    <div class="tareas">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>ID_Tarea</th>
                                                    <th>Nombre_Subcategoría</th>
                                                    <th>Fecha</th>
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
                                                        <td><?php echo htmlspecialchars($tarea['fecha']); ?></td>
                                                        <td><?php echo htmlspecialchars($tarea['turno']); ?></td>
                                                        <td>
                                                            <?php
                                                             
                                                            if ($tarea['estado'] == 'COMPLETADA') {
                                                                echo '<span style="color: rgb(41, 250, 41); background-color: black;">' . htmlspecialchars($tarea['estado']) . '</span>';
                                                                
                                                            }elseif ($tarea['estado'] === 'PENDIENTE') {
                                                                $tarea_NOCOMPLETADA = false;
                                        
                                                                if ($fecha_actual >= $tarea['fecha']) {
                                                                    if ($tarea['turno'] === 'Mañana' && ($turno_Tarde || $turno_Noche)) {
                                                                        $tarea_NOCOMPLETADA = true;
                                                                    }
                                                                    if ($tarea['turno'] === 'Tarde' && $turno_Noche) {
                                                                        $tarea_NOCOMPLETADA = true;
                                                                    }
                                                                    if ($tarea['turno'] === 'Noche' && $hora_actual > $turno_NocheFin) {
                                                                        $tarea_NOCOMPLETADA = true;
                                                                    }
                                        
                                                                    if ($tarea_NOCOMPLETADA) {
                                                                        $id_tarea = intval($tarea['id_tarea']);
                                                                        $nocompletada_query = "UPDATE tareas SET estado='NO COMPLETADO' WHERE id_tarea=$id_tarea";
                                                                        $resultado = mysqli_query($conexion, $nocompletada_query);
                                        
                                                                        if ($resultado) {
                                                                            // Recargar el estado actualizado desde la base de datos
                                                                            $query_tarea_actualizada = "SELECT estado FROM tareas WHERE id_tarea=$id_tarea";
                                                                            $resultado_actualizado = mysqli_query($conexion, $query_tarea_actualizada);
                                        
                                                                            if ($resultado_actualizado && mysqli_num_rows($resultado_actualizado) > 0) {
                                                                                $tarea_actualizada = mysqli_fetch_assoc($resultado_actualizado);
                                                                                $tarea['estado'] = $tarea_actualizada['estado'];
                                                                            }
                                        
                                                                            echo '<span style="color: red; background-color: black;">' . htmlspecialchars($tarea['estado']) . '</span>';
                                                                        } else {
                                                                            echo '<span style="color: white; background-color: red;">Error al actualizar estado</span>';
                                                                        }
                                                                    } else {
                                                                        echo '<span style="color: orange; background-color: black;">' . htmlspecialchars($tarea['estado']) . '</span>';
                                                                    }
                                                                } else {
                                                                    echo '<span style="color: orange; background-color: black;">' . htmlspecialchars($tarea['estado']) . '</span>';
                                                                }
                                                            }else {
                                                                echo '<span style="color: red; background-color: black;">' . htmlspecialchars($tarea['estado']) . '</span>';

                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if(($tarea['estado'] == 'COMPLETADA')||($tarea['estado'] == 'PENDIENTE')){
                                                                ?>
                                                                <form action="gestionTareas.php?paso=7" method="post" id="form-completar-<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                                                    <button type="submit" value="Completar" class="contboton"><img src="../img/botonCompletar.png" alt="botonEliminar" width="125px" height="20px"></button>
                                                                    <input type="hidden" name="id_tarea" value="<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                                                    <input type="hidden" name="EXP" value="<?php echo $EXP_usuario; ?>">
                                                                </form>
                                                                <?php
                                                            }
                                                            ?>
                                                            <script>
                                                                document.querySelector("#form-completar-<?php echo htmlspecialchars($tarea['id_tarea']); ?> button[type='submit']").addEventListener('click', function(event) {
                                                                    // Evita que el formulario se envíe inmediatamente
                                                                    event.preventDefault();
                                                                    
                                                                    <?php
                                                                    

                                                                    if($tarea['estado'] == 'COMPLETADA'){
                                                                        ?>
                                                                        const confirmacion = confirm("[YA COMPLETADA] Acepta que quieres completar la tarea N° <?php echo $tarea['id_tarea']; ?>");
                                                                        <?php
                                                                    }else if($tarea['estado'] == 'PENDIENTE'){
                                                                        ?>
                                                                        const confirmacion = confirm("[PENDIENTE] Acepta que quieres completar la tarea N° <?php echo $tarea['id_tarea']; ?>");
                                                                        <?php
                                                                    }else{
                                                                        ?>
                                                                        const confirmacion = confirm("[PENDIENTE] Acepta que quieres completar la tarea N° <?php echo $tarea['id_tarea']; ?>");
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                        // Muestra una ventana de confirmación
                                                                    
                                                                    if (confirmacion) {
                                                                        // Si el usuario hace clic en "Aceptar", envía el formulario
                                                                        document.querySelector("#form-completar-<?php echo htmlspecialchars($tarea['id_tarea']); ?>").submit();
                                                                    } 
                                                                });
                                                            </script>

                                                        </td>

                                                            
                                                        </td>
                                                        
                                                    </tr>
                                                        <td class="boton-eliminar">
                                                            <form action="gestionTareas.php?paso=8" method="post" id="form-eliminar-<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                                                <button type="submit" value="Eliminar" class="contboton"><img src="../img/botonEliminar.png" alt="botonEliminar" width="125px" height="20px"></button>
                                                                <input type="hidden" name="id_tarea" value="<?php echo htmlspecialchars($tarea['id_tarea']); ?>">
                                                            </form>
                                                        </td>
                                                        
                                                        <script>
                                                                document.querySelector("#form-eliminar-<?php echo htmlspecialchars($tarea['id_tarea']); ?> button[type='submit']").addEventListener('click', function(event) {
                                                                    // Evita que el formulario se envíe inmediatamente
                                                                    event.preventDefault();
                                                                    
                                                                    const confirmacion = confirm("¿Estás seguro de que quieres eliminar la tarea N° <?php echo htmlspecialchars($tarea['id_tarea']); ?>?");
                                                                    
                                                                    if (confirmacion) {
                                                                        // Si el usuario confirma, envía el formulario
                                                                        document.querySelector("#form-eliminar-<?php echo htmlspecialchars($tarea['id_tarea']); ?>").submit();
                                                                    }
                                                                });
                                                            </script>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="botonAgregarTareas">
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                                    
                    <div class="right">
                        <!--3er columna del grid-->
                        <h3 style="text-align: center;">TAREAS PARA HOY</h3>

                        <?php
                        // Obtener la fecha actual
                        $fecha_actual = new DateTime();
                                                
                        // Convertir la fecha a formato 'YYYY-MM-DD'
                        $fecha_actual = $fecha_actual->format('Y-m-d');
                    

                        //CONSULTA PARA OBTENER 'ID_USUARIO'
                        $id_usuario = "SELECT id_usuario FROM usuarios WHERE nombre_usuario='$nombre_usuario';";
                        $id_usuario = mysqli_query($conexion, $id_usuario);
                        $id_usuario = mysqli_fetch_row($id_usuario);
                        $id_usuario = $id_usuario[0];

                        // Consulta optimizada para obtener solo las tareas de hoy
                        $consultaTareasHoy = "SELECT * FROM rutinaview WHERE fecha = '$fecha_actual' AND nombre_usuario='$nombre_usuario';"; 
                        $resultadoTareas = mysqli_query($conexion, $consultaTareasHoy);
                        
                        $contadorTareas=0;

                        
                        if ($resultadoTareas && mysqli_num_rows($resultadoTareas) > 0) {
                            // Contador para las tareas mostradas
                            $contadorTareas = 0;
                            ?>
                            <ul>
                            <?php
                            while (($tarea = mysqli_fetch_assoc($resultadoTareas)) && $contadorTareas <= 9) {

                                // Mostrar tarea según su estado
                                if ($tarea['estado'] == 'COMPLETADA') {
                                    echo "<li><i><s>" . htmlspecialchars($tarea['nombre_subcategoria']) . "</s></i></li>";
                                    ?>
                                    <br>
                                    <?php
                                } else {
                                    echo "<li><i>" . htmlspecialchars($tarea['nombre_subcategoria']) . "</i></li>";
                                    ?>
                                    <br>
                                    <?php
                                }

                                // Incrementar el contador de tareas mostradas
                                $contadorTareas++;
                            }
                            ?>
                            </ul>
                            <?php
                        } else {
                            echo "<p>¡Enhorabuena! No tienes tareas para hoy</p>";
                        }
                        if($contadorTareas>=10){
                            echo "<p>¡ALTO AHÍ, ES MUCHO POR HOY! Termina con alguna tarea y vuelve luego</p>";
                        }
                        ?>
                    </div>
         
                <?php
            }
        ?>
    
    <script src="../js/script.js"></script>

</body>
</html>