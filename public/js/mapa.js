// Esperamos a que la página cargue antes de crear el mapa
document.addEventListener('DOMContentLoaded', function () {
    // Inicializamos el mapa centrado en Buenos Aires
    var map = L.map('map').setView([-34.61, -58.38], 5);

    // Capa base (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    // Cuando el usuario hace clic en el mapa
    map.on('click', function (e) {
        var lat = e.latlng.lat;
        var lon = e.latlng.lng;

        // Si ya hay un marcador, lo movemos; si no, lo creamos
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        // Guardamos las coordenadas en los inputs ocultos
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lon;
    });
});
