document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.querySelectorAll("tbody")[0];    
    const inputBuscar = document.getElementById("buscar");

    const API_SOLICITUDES_URL = '/index.php?api=solicitudes';

    async function fetchSolicitudes() {
        try {
            const response = await fetch(API_SOLICITUDES_URL, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
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

            const cvVistoTexto = solicitud.cv_visto ? 'Sí' : 'No';

            c1.innerHTML = solicitud.id || '';
            c2.innerHTML = solicitud.oferta_titulo;
            c3.innerHTML = solicitud.fecha_solicitud;
            c4.innerHTML = cvVistoTexto;

            fila.appendChild(c1);
            fila.appendChild(c2);
            fila.appendChild(c3);
            fila.appendChild(c4);
            tbody.appendChild(fila);
        });
    }

    fetchSolicitudes();
});