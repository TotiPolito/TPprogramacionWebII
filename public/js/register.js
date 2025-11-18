document.addEventListener("DOMContentLoaded", function() {
    const inputMail = document.querySelector('input[name="mail"]');
    const form = document.querySelector('.form');

    // Creamos un span donde mostrar el mensaje
    const avisoMail = document.createElement('span');
    avisoMail.style.display = "block";
    avisoMail.style.marginTop = "5px";
    avisoMail.style.color = "red";
    inputMail.insertAdjacentElement("afterend", avisoMail);

    // Cada vez que cambia el email, lo verificamos
    inputMail.addEventListener("blur", function() {
        const mail = inputMail.value.trim();
        if (!mail) return; // no enviar vacío

        fetch("{{BASE_URL}}/Register/verificarMailAjax", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "mail=" + encodeURIComponent(mail)
        })
            .then(res => res.json())
            .then(data => {
                if (data.existe) {
                    avisoMail.textContent = "Este correo ya está registrado.";
                    avisoMail.style.color = "red";
                } else {
                    avisoMail.textContent = "Correo disponible.";
                    avisoMail.style.color = "green";
                }
            })
            .catch(() => {
                avisoMail.textContent = "Error verificando el correo.";
                avisoMail.style.color = "red";
            });
    });
});
