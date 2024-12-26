document.getElementById("esconderBoton").addEventListener("click", function(event) {
    const audio = document.getElementById("audio");
    audio.play(); // Reproduce el audio

    // Agregar un pequeño retraso antes de enviar el formulario
    setTimeout(() => {
        this.closest("form").submit(); // Envía el formulario
    }, 500); // 500ms es suficiente para permitir que se reproduzca el sonido
    event.preventDefault(); // Evita el envío inmediato del formulario
});


// Seleccionar el botón
const cambiarFotoBtn = document.getElementById('cambiarFotoBtn');

// Agregar evento click
cambiarFotoBtn.addEventListener('click', () => {
    // Mostrar un prompt para que el usuario introduzca una URL
    const fotoPerfil = prompt("Ingrese la URL de la nueva imagen:");

    // Validar que el usuario haya ingresado algo
    if (fotoPerfil) {
        // Redirigir a gestionTareas.php con el paso 9 y la nueva URL
        window.location.href = `gestionTareas.php?paso=9&fotoNueva=${encodeURIComponent(fotoPerfil)}`;
    } else {
        alert("No ingresaste ninguna URL.");
    }
});

                                
