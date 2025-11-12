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