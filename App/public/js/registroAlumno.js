document.addEventListener("DOMContentLoaded", function () {

    const API_FAMILIAS_URL = '/index.php?api=familias';
    const API_CICLOS_URL = '/index.php?api=ciclos';

    async function obtenerFamilias(idElementoSelect, valorSeleccionado = null) {
        const select = document.getElementById(idElementoSelect);
        select.innerHTML = '<option value="" disabled selected>Cargando familias...</option>';
        try {
            const response = await fetch(API_FAMILIAS_URL);
            const result = await response.json();

            if (result.success) {
                select.innerHTML = '<option value="" disabled selected>Selecciona una familia</option>';
                result.data.forEach(familia => {
                    const option = document.createElement('option');
                    option.value = familia.id;
                    option.textContent = familia.nombre;
                    if (valorSeleccionado !== null && familia.id == valorSeleccionado) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="" disabled selected>Error al cargar familias</option>';
            }
        } catch (error) {
            select.innerHTML = '<option value="" disabled selected>Error de conexión</option>';
        }
    }

    async function obtenerCiclos(idElementoSelect, idFamilia = null, valorSeleccionado = null) {
        const select = document.getElementById(idElementoSelect);
        select.innerHTML = '<option value="" disabled selected>Cargando ciclos...</option>';
        let url = API_CICLOS_URL;
        if (idFamilia) {
            url += `&familia_id=${idFamilia}`;
        }

        try {
            const response = await fetch(url);
            const result = await response.json();

            if (result.success) {
                select.innerHTML = '<option value="" disabled selected>Selecciona un ciclo</option>';
                if (result.data.length === 0) {
                    select.innerHTML = '<option value="" disabled>No hay ciclos para esta familia</option>';
                }
                result.data.forEach(ciclo => {
                    const option = document.createElement('option');
                    option.value = ciclo.id;
                    option.textContent = ciclo.nombre;
                    if (valorSeleccionado !== null && ciclo.id == valorSeleccionado) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="" disabled selected>Error al cargar ciclos</option>';
            }
        } catch (error) {
            select.innerHTML = '<option value="" disabled selected>Error de conexión</option>';
        }
    }

    obtenerFamilias('addFamilia');
    obtenerCiclos('addCiclo');

    document.getElementById('addFamilia').addEventListener('change', (event) => {
        const idFamiliaSeleccionada = event.target.value;
        if (idFamiliaSeleccionada) {
            obtenerCiclos('addCiclo', idFamiliaSeleccionada);
        } else {
            document.getElementById('addCiclo').innerHTML = '<option value="" disabled selected>Selecciona un ciclo</option>';
        }
    });

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
    let fotoCapturadaPorCamara = null;

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
        fotoCapturadaPorCamara = null; 

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

    inputFoto.onchange = function(e) {
        if (e.target.files && e.target.files[0]) {
            detenerCamara();
            fotoCapturadaPorCamara = null; 
            let lectorArchivos = new FileReader();
            lectorArchivos.onload = function(evento) {
                let imagen = new Image();
                imagen.onload = function() {
                    contextoCanvas.clearRect(0, 0, elementoCanvas.width, elementoCanvas.height);
                    contextoCanvas.drawImage(imagen, 0, 0, ANCHO_VIDEO, ALTO_VIDEO);
                    elementoCanvas.style.display = 'block';
                };
                imagen.src = evento.target.result;
            };
            lectorArchivos.readAsDataURL(e.target.files[0]);
        } else {
            limpiarCanvasYOcultar();
        }
    };

    botonCapturar.addEventListener('click', function() {
        elementoVideo.style.display = 'none';
        botonCapturar.style.display = 'none';
        elementoCanvas.style.display = 'block';
        
        contextoCanvas.drawImage(elementoVideo, 0, 0, ANCHO_VIDEO, ALTO_VIDEO);
        
        detenerCamara();

        elementoCanvas.toBlob(function(blob) {
            if (blob) {
                fotoCapturadaPorCamara = new File([blob], "foto_capturada.jpeg", { type: "image/jpeg" });
            }
        }, 'image/jpeg');

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
                inputFoto.value = '';
                fotoCapturadaPorCamara = null;
            } else {
                iniciarCamara();
                botonActivarCamara.textContent = "Desactivar Cámara";
                inputFoto.value = '';
            }
        });
    }

    detenerCamara();
    limpiarCanvasYOcultar();
    if (botonActivarCamara) {
        botonActivarCamara.textContent = "Activar Cámara";
    }

    let formulario = document.getElementById('registroAlumnoForm') || document.forms[0]; 
    
    formulario.addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const reppassword = document.getElementById('reppassword').value;
        const nombre = document.getElementById('nombre').value;
        const apellidos = document.getElementById('apellidos').value;
        const familiaId = document.getElementById('addFamilia').value;
        const cicloId = document.getElementById('addCiclo').value;
        const telefono = document.getElementById('telefono').value;
        const direccion = document.getElementById('direccion').value;
        const cvFileSelected = document.getElementById('cv').files[0];
        const fotoFileManuallySelected = document.getElementById('foto').files[0];


        if (!email || email.trim() === '') { alert("El email es obligatorio."); return; }
        if (!password || password.trim() === '') { alert("La contraseña es obligatoria."); return; }
        if (!reppassword || reppassword.trim() === '') { alert("Repetir contraseña es obligatorio."); return; }
        if (password !== reppassword) { alert("Las contraseñas no coinciden."); return; }
        if (!nombre || nombre.trim() === '') { alert("El nombre es obligatorio."); return; }
        if (!apellidos || apellidos.trim() === '') { alert("Los apellidos son obligatorios."); return; }
        if (!familiaId || familiaId.trim() === '') { alert("La familia profesional es obligatoria."); return; }
        if (!cicloId || cicloId.trim() === '') { alert("El ciclo formativo es obligatorio."); return; }
        if (!telefono || telefono.trim() === '') { alert("El teléfono es obligatorio."); return; }
        if (!direccion || direccion.trim() === '') { alert("La dirección es obligatoria."); return; }
        
        let finalFotoFile = fotoCapturadaPorCamara;
        if (!finalFotoFile && fotoFileManuallySelected) {
            finalFotoFile = fotoFileManuallySelected;
        }

        if (!finalFotoFile) { alert("La foto de perfil es obligatoria."); return; }
        if (!cvFileSelected) { alert("El CV es obligatorio."); return; }
        
        if (cvFileSelected.type !== 'application/pdf') { alert("El CV debe ser un archivo PDF."); return; }


        const formData = new FormData(formulario); 
        
        formData.set('foto', finalFotoFile, finalFotoFile.name); 

        fetch(formulario.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                console.error("Respuesta inesperada del servidor:", response);
                alert("Hubo un problema con la respuesta del servidor o la redirección.");
            }
        })
        .catch(error => {
            console.error('Error al enviar el formulario:', error);
            alert('Ocurrió un error inesperado al registrar. Intenta de nuevo.');
        });
    });

});
