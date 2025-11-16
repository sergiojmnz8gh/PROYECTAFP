document.addEventListener("DOMContentLoaded", function () {

    const API_FAMILIAS_URL = '/index.php?api=familias';
    const API_CICLOS_URL = '/index.php?api=ciclos';

    async function fetchFamilias(selectElementId, selectedValue = null) {
        const select = document.getElementById(selectElementId);

        select.innerHTML = '<option value="">Cargando familias...</option>';
        try {
            const response = await fetch(API_FAMILIAS_URL);
            const result = await response.json();

            if (result.success) {
                select.innerHTML = '<option value="">Selecciona una familia</option>';
                result.data.forEach(familia => {
                    const option = document.createElement('option');
                    option.value = familia.id;
                    option.textContent = familia.nombre;
                    if (selectedValue !== null && familia.id == selectedValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="">Error al cargar familias</option>';
            }
        } catch (error) {
            select.innerHTML = '<option value="">Error de conexión</option>';
        }
    }

    async function fetchCiclos(selectElementId, familiaId = null, selectedValue = null) {
        const select = document.getElementById(selectElementId);

        select.innerHTML = '<option value="">Cargando ciclos...</option>';
        let url = API_CICLOS_URL;
        if (familiaId) {
            url += `&familia_id=${familiaId}`;
        }

        try {
            const response = await fetch(url);
            const result = await response.json();

            if (result.success) {
                select.innerHTML = '<option value="">Selecciona un ciclo</option>';
                if (result.data.length === 0) {
                    select.innerHTML = '<option value="" disabled>No hay ciclos para esta familia</option>';
                }
                result.data.forEach(ciclo => {
                    const option = document.createElement('option');
                    option.value = ciclo.id;
                    option.textContent = ciclo.nombre;
                    if (selectedValue !== null && ciclo.id == selectedValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="">Error al cargar ciclos</option>';
            }
        } catch (error) {
            select.innerHTML = '<option value="">Error de conexión</option>';
        }
    }

    fetchFamilias('addFamilia');
    fetchCiclos('addCiclo');

    document.getElementById('addFamilia').addEventListener('change', (event) => {
        const selectedFamiliaId = event.target.value;
        if (selectedFamiliaId) {
            fetchCiclos('addCiclo', selectedFamiliaId);
        } else {
            document.getElementById('addCiclo').innerHTML = '<option value="">Selecciona un ciclo</option>';
        }
    });

    window.addEventListener("load", inicializarLogicaCamaraYFoto);

function inicializarLogicaCamaraYFoto() {
    let inputFoto = document.getElementById('foto');
    let elementoVideo = document.getElementById('video');
    let botonCapturar = document.getElementById('snap');
    let elementoCanvas = document.getElementById('canvas');
    let botonActivarCamara = document.getElementById('abrirCamara');
    let contextoCanvas = elementoCanvas.getContext('2d');

    const ANCHO_VIDEO = 480;
    const ALTO_VIDEO = 480;

    elementoCanvas.width = ANCHO_VIDEO;
    elementoCanvas.height = ALTO_VIDEO;

    let streamCamaraActual = null;

    function detenerCamara() {
        if (streamCamaraActual) {
            streamCamaraActual.getTracks().forEach(pista => pista.stop());
            elementoVideo.srcObject = null;
            streamCamaraActual = null;
        }
        elementoVideo.style.display = 'none';
        botonCapturar.style.display = 'none';
        let botonRepetirExistente = document.getElementById('botonRepetir');
        if (botonRepetirExistente) {
            botonRepetirExistente.remove();
        }
    }

    function iniciarCamara() {
        detenerCamara();
        elementoCanvas.style.display = 'none';

        navigator.mediaDevices.getUserMedia({ video: { width: ANCHO_VIDEO, height: ALTO_VIDEO } })
            .then(stream => {
                streamCamaraActual = stream;
                elementoVideo.srcObject = stream;
                elementoVideo.style.display = 'block';
                botonCapturar.style.display = 'block';
                elementoVideo.play();
            })
            .catch(error => {
                alert("No se pudo acceder a la cámara. Asegúrate de tener una cámara y dar permisos.");
                detenerCamara();
            });
    }

    function limpiarCanvasYOcultar() {
        contextoCanvas.clearRect(0, 0, elementoCanvas.width, elementoCanvas.height);
        elementoCanvas.style.display = 'none';
    }

    function dibujarImagenEnCanvas(rutaImagen) {
        detenerCamara();

        let imagen = new Image();
        imagen.onload = function() {
            contextoCanvas.clearRect(0, 0, elementoCanvas.width, elementoCanvas.height);
            let relacionAspecto = imagen.width / imagen.height;
            let anchoDibujo = elementoCanvas.width;
            let altoDibujo = elementoCanvas.height;

            if (anchoDibujo / altoDibujo > relacionAspecto) {
                anchoDibujo = altoDibujo * relacionAspecto;
            } else {
                altoDibujo = anchoDibujo / relacionAspecto;
            }
            let posX = (elementoCanvas.width - anchoDibujo) / 2;
            let posY = (elementoCanvas.height - altoDibujo) / 2;

            contextoCanvas.drawImage(imagen, posX, posY, anchoDibujo, altoDibujo);
            elementoCanvas.style.display = 'block';
        };
        imagen.src = rutaImagen;
    }

    inputFoto.onchange = function(e) {
        if (e.target.files && e.target.files[0]) {
            let lectorArchivos = new FileReader();
            lectorArchivos.onload = function(evento) {
                dibujarImagenEnCanvas(evento.target.result);
            };
            lectorArchivos.readAsDataURL(e.target.files[0]);
        } else {
            limpiarCanvasYOcultar();
            detenerCamara();
        }
    };

    botonCapturar.addEventListener('click', function() {
        elementoVideo.style.display = 'none';
        botonCapturar.style.display = 'none';
        elementoCanvas.style.display = 'block';
        
        contextoCanvas.drawImage(elementoVideo, 0, 0, ANCHO_VIDEO, ALTO_VIDEO);
        
        detenerCamara();

        let botonRepetir = document.getElementById('botonRepetir');
        if (!botonRepetir) {
            botonRepetir = document.createElement("button");
            botonRepetir.id = 'botonRepetir';
            botonRepetir.textContent = "Repetir";
            elementoCanvas.after(botonRepetir);
        } else {
            botonRepetir.style.display = 'block';
        }

        botonRepetir.onclick = function() {
            iniciarCamara();
            botonRepetir.style.display = 'none';
        };
    });

    if (botonActivarCamara) {
        botonActivarCamara.addEventListener('click', function() {
            if (streamCamaraActual) {
                detenerCamara();
                botonActivarCamara.textContent = "Activar Cámara";
                limpiarCanvasYOcultar();
            } else {
                iniciarCamara();
                botonActivarCamara.textContent = "Desactivar Cámara";
            }
        });
    }

    detenerCamara();
    limpiarCanvasYOcultar();
    if (botonActivarCamara) {
        botonActivarCamara.textContent = "Activar Cámara";
    }

    // Lógica para enviar el formulario con la imagen del canvas
    let formulario = document.forms[0]; // Asume que es el primer formulario
    let botonEnviar = formulario["enviar"]; // Asume un input submit con name="enviar"

    botonEnviar.onclick = function(e) {
        e.preventDefault();
        let datosFormulario = new FormData(formulario);

        // Obtener la imagen del canvas como un archivo Blob y añadirla al FormData
        // Esto es crucial para enviar la imagen correctamente por POST
        elementoCanvas.toBlob(function(blob) {
            if (blob) {
                // 'foto' es el nombre que tu backend PHP esperaría para el archivo de la imagen
                datosFormulario.append('foto', blob, 'foto_capturada.png'); 
            } else {
                console.warn("No hay imagen en el canvas para enviar.");
            }

            // Realizar la petición fetch con el FormData que ahora incluye la imagen
            fetch("php/index.php", {
                method: "post",
                body: datosFormulario
            })
            .then((respuesta) => respuesta.text())
            .then((texto) => {console.log(texto)});
        }, 'image/png'); // Puedes cambiar a 'image/jpeg' si prefieres ese formato
    };
}
});