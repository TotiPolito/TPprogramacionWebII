let tiempo = 10;
const contador = document.getElementById("tiempo");
const form = document.getElementById("respuestaForm");
const inicio = Date.now();

const inputTiempoInicio = document.createElement("input");
inputTiempoInicio.type = "hidden";
inputTiempoInicio.name = "tiempoInicio";
inputTiempoInicio.value = inicio;
form.appendChild(inputTiempoInicio);

const interval = setInterval(() => {
    tiempo--;
    contador.textContent = tiempo;

    if (tiempo <= 0) {
        clearInterval(interval);
        enviarTimeout();
    }
}, 1000);

function enviarRespuesta(idRespuesta, botonSeleccionado) {
    clearInterval(interval);
    const esCorrecta = botonSeleccionado.getAttribute('data-correcta') === '1';
    if (esCorrecta) {
        botonSeleccionado.classList.add('correcta');
    } else {
        botonSeleccionado.classList.add('incorrecta');
    }
    document.querySelectorAll('.respuestas button').forEach(b => b.disabled = true);
    setTimeout(() => {
        document.getElementById("idRespuesta").value = idRespuesta;
        form.submit();
    }, 1000);
}

function enviarTimeout() {
    document.getElementById("idRespuesta").value = 0;
    const inputTimeout = document.createElement("input");
    inputTimeout.type = "hidden";
    inputTimeout.name = "timeout";
    inputTimeout.value = "true";
    form.appendChild(inputTimeout);
    form.submit();
}

(function(){
    const HOME_URL = "{{BASE_URL}}/index.php?controller=Home&method=Game";
    const SIGUIENTE_URL = "{{BASE_URL}}/index.php?controller=Game&method=jugar";

    window.history.pushState(null, "", window.location.href);
    window.addEventListener("popstate", function () {
        window.location.replace(HOME_URL);
    });

    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            window.location.replace(HOME_URL);
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const link = document.querySelector('.link_respuesta[href*="controller=Game&method=jugar"]');
        if (link) {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                window.location.replace(SIGUIENTE_URL);
            });
        }
    });
})();