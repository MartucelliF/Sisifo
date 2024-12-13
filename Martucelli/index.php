<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>prueba de HTML con PHP</title>
</head>
<body>

    <?php

    $sesionIniciada = false;

    ?>

    <div class="centrar">
        <form action="php/inicioSesion.php" class="formInicioSesion" method="post">
            <fieldset class="datosUsuario">
                <legend><b>INICIAR SESIÓN</b></legend>
                <label for="nombre_usuario"><b>Nombre de usuario: </b></label>
                <input type="text" name="nombre_usuario" id="nombre_usuario" required>
                <br>
                <label for="correo_usuario"><b>Correo electrónico: </b></label>
                <input type="email" name="correo_usuario" id="correo_usuario" placeholder="ejemplo@gmail.com" required>
            </fieldset>
        
            sesionIniciada=  <?php echo $sesionIniciada ?>;
                    
            <input type="hidden" name="sesionIniciada" value="<?php echo $sesionIniciada; ?>">

            <input type="submit" name="comun2" value="ACCEDER">        
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
</body>
</html>