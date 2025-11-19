document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.querySelectorAll("tbody")[0];
    const API_SOLICITUDES_URL = '/index.php?api=solicitudes';
    
    const url = window.location.search;
    const urlParams = new URLSearchParams(url);
    const ofertaId = urlParams.get('ofertaId');

    async function fetchSolicitudes() {
        const url = `${API_SOLICITUDES_URL}&ofertaId=${encodeURIComponent(ofertaId)}`;
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
            }

            const solicitudesJson = await response.json();
            pintarTabla(solicitudesJson);
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="5">Error al cargar las solicitudes. Por favor, intente de nuevo más tarde.</td></tr>';
        }
    }

    function pintarTabla(solicitudesJson) {
        tbody.innerHTML = "";

        if (!solicitudesJson || !Array.isArray(solicitudesJson) || solicitudesJson.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6">No hay solicitudes para mostrar.</td></tr>';
            return;
        }

        solicitudesJson.forEach(solicitud => {
            let fila = document.createElement("tr");
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            let c5 = document.createElement("td");

            const cvVistoTexto = solicitud.cv_visto ? 'Sí' : 'No';
            const ofertaTitulo = solicitud.oferta_titulo;
            let titulo = document.getElementById('titulo');
            titulo.innerHTML ="Solicitudes de la oferta: " + ofertaTitulo;

            c1.innerHTML = solicitud.id || '';
            c2.innerHTML = solicitud.alumno_nombre;
            c3.innerHTML = solicitud.alumno_apellidos;
            c4.innerHTML = formatTimestamp(solicitud.fecha_solicitud);
            c5.innerHTML = cvVistoTexto;

            fila.appendChild(c1);
            fila.appendChild(c2);
            fila.appendChild(c3);
            fila.appendChild(c4);
            fila.appendChild(c5);
            tbody.appendChild(fila);
        });
    }

    fetchSolicitudes();

    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    }
});