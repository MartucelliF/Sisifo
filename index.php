<!DOCTYPE html>
<html lang="es" class="iniciojsp">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    
    <link rel="icon" type="image/jpg" href="img/icon.png"/>
    
    <title>SÍSIFO: Iniciar Sesión</title>
</head>
<body class="iniciojsp">
    <?php
    if (isset($_GET['sesionCerrada']) && $_GET['sesionCerrada'] == 1) {
    ?>
        <audio src="audio/cerrarSesion.mp3" autoplay></audio>
    <?php
    }
    ?>
    <div class="display">
        <div class="centrar">
            <form action="php/inicioSesion.php" class="formInicioSesion" method="post">
                <fieldset class="datosUsuario">
                    <br><br><br><br>
                    <label for="correo_usuario"><b>Correo electrónico: </b></label>
                    <input type="email" name="correo_usuario" id="correo_usuario" placeholder="ejemplo@gmail.com" required>
                    <br>
                    <label for="clave_usuario"><b>Contraseña: </b></label>
                    <input type="password" name="clave_usuario" id="clave_usuario" required>
                    <br>
                    <br><br><br><br><br>
                </fieldset>
                <br>               
                    <button type="submit" class="contboton"><img src="img/botonInicioSesion.png" alt="botonInicioSesion" width="250px" height="40px"></button>
                </form>
            <br><br>
            Si no tiene una cuenta, puede <a href="registro.html"><i>registrarse</i></a>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Realizar una solicitud AJAX al archivo PHP
                    fetch('obtenerValor.php')
                        .then(response => response.json())
                        .then(data => {
                            // Actualizar el contenido del HTML basado en la respuesta
                            document.getElementById('miVariable').textContent = data.miVariable;
                        })
                        .catch(error => console.error('Error:', error));
                });
            </script>
        </div>
    </div>
</body>
</html>