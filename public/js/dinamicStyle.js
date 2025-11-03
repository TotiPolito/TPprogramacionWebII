const boton = document.querySelector('.btn_js');
const popUp = document.querySelector('.select_box');
const titulo = document.querySelector('h2');
const botonRanking = document.getElementById('btnRanking');
const botonPartida = document.getElementById('btnPartida');

boton.addEventListener('click', function() {

    popUp.style.display = 'flex';
    boton.style.display = 'none';
    titulo.style.display = 'none';
    botonRanking.style.display = 'none';
    botonPartida.style.display = 'none';
});

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