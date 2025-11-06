document.addEventListener("DOMContentLoaded", () => {
    const music = document.getElementById("backgroundMusic");
    const toggle = document.getElementById("toggleMusic");
    const volume = document.getElementById("volumeControl");

    const lastTime = parseFloat(localStorage.getItem("musicaTiempo")) || 0;
    const lastState = localStorage.getItem("musica") || "off";
    const lastVol = parseFloat(localStorage.getItem("musicaVolumen"));
    const initialVol = !isNaN(lastVol) ? lastVol : 0.5;


    music.currentTime = Math.min(lastTime, music.duration || Infinity); // evita errores si duration no cargó
    music.volume = initialVol;
    volume.value = initialVol;

    if (lastState === "on") {

        const playPromise = music.play();
        if (playPromise !== undefined) {
            playPromise.catch(() => {
                toggle.textContent = "🎵";
            });
        }
        toggle.textContent = "⏸";
    } else {
        toggle.textContent = "🎵";
    }

    toggle.addEventListener("click", () => {
        if (music.paused) {
            music.play().catch(() => {  });
            toggle.textContent = "⏸";
            localStorage.setItem("musica", "on");
        } else {
            music.pause();
            toggle.textContent = "🎵";
            localStorage.setItem("musica", "off");
        }
    });

    // Volumen
    volume.addEventListener("input", (e) => {
        music.volume = parseFloat(e.target.value);
        localStorage.setItem("musicaVolumen", music.volume);
    });

    // Guardar tiempo cada 2s para no saturar localStorage
    let saveInterval = setInterval(() => {
        try {
            localStorage.setItem("musicaTiempo", music.currentTime);
        } catch (e) { }
    }, 2000);

    window.addEventListener("beforeunload", () => {
        try {
            localStorage.setItem("musicaTiempo", music.currentTime);
        } catch (e) {}
    });
});
