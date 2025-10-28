const boton = document.querySelector('.btn_js');
const popUp = document.querySelector('.select_box');

boton.addEventListener('click', function() {

    popUp.style.display = 'flex';
});

function enviarRespuesta(idRespuesta) {
    // Seteamos la respuesta seleccionada y enviamos el formulario
    document.getElementById('idRespuesta').value = idRespuesta;
    document.getElementById('respuestaForm').submit();
}