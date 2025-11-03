document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([-34.61, -58.38], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker;

    map.on('click', function (e) {
        var lat = e.latlng.lat;
        var lon = e.latlng.lng;

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }

        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lon;
    });
});
