window.addEventListener("load", cargarLogica);

window.addEventListener("load", conectarCamara);

function cargarLogica() {
    let form = document.forms[0];
    let btn = form["enviar"];
    btn.onclick = function(e) {
        e.preventDefault();
        let datos= new FormData(form);
        fetch("php/index.php", {
            method: "post",
            body: datos
        }).then((respuesta)=>respuesta.text())
        .then((texto)=>{console.log(texto)});
    }
}

function conectarCamara() {
    const video = document.getElementById('video');
    const snap = document.getElementById('snap');
    const canvas = document.getElementById('canvas');
    const guardar = document.getElementById('guardar');

    const WIDTH = 480;
    const HEIGHT = 480;

    const constraints = {
        video: {
        width: WIDTH, 
        height: HEIGHT
        }
    };

    canvas.width = WIDTH;
    canvas.height = HEIGHT;
    
    let context = canvas.getContext('2d');
    
    async function init() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = stream;
        } catch (e) {
            console.error('Error al conectar la cámara: ' + e.toString()); 
        }
    }
    init();

    snap.addEventListener('click', function() {
        video.style.display = "none";
        snap.style.display = "none";
        canvas.style.display = "block";
        context.drawImage(video, 0, 0, WIDTH, HEIGHT); 
        let btnRepetir = document.createElement("button");
        btnRepetir.textContent = "Repetir";
        btnRepetir.onclick = function() {
            video.style.display = "block";
            snap.style.display = "block";
            canvas.style.display = "none";
            btnRepetir.style.display = "none";
        }
        canvas.after(btnRepetir);
    });
}