function enviarRespuesta(idRespuesta, botonSeleccionado) {
    const esCorrecta = botonSeleccionado.getAttribute('data-correcta') === '1';

    // se cambia el color del botón según sea correcta o no
    if (esCorrecta) {
        botonSeleccionado.classList.add('correcta');
    } else {
        botonSeleccionado.classList.add('incorrecta');
    }

    const botones = document.querySelectorAll('.respuestas button');
    botones.forEach(b => b.disabled = true);

    setTimeout(() => {
        document.getElementById('idRespuesta').value = idRespuesta;
        document.getElementById('respuestaForm').submit();
    }, 1000);
}