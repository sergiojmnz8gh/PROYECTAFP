document.addEventListener('DOMContentLoaded', () => {
    const carruselPista = document.querySelector('.carrusel-pista');
    const itemsOriginales = document.querySelectorAll('.carrusel-item');
    const seccionEmpresas = document.querySelector('.seccion-empresas-colaboradoras');
    
    if (!carruselPista || itemsOriginales.length === 0) {
        return;
    }

    let indiceActual = 0;
    const totalItemsOriginales = itemsOriginales.length;
    let itemsVisibles = 4;
    let intervaloAutomatico;

    for (let i = 0; i < itemsVisibles; i++) {
        carruselPista.appendChild(itemsOriginales[i].cloneNode(true));
    }
    
    function calcularItemsVisibles() {
        if (window.innerWidth <= 576) {
            return 1;
        } else if (window.innerWidth <= 992) {
            return 2;
        } else {
            return 3;
        }
    }
    
    function aplicarTransicion(habilitar) {
        carruselPista.style.transition = habilitar ? 'transform 1.0s ease-in-out' : 'none';
    }

    function actualizarPosicion() {
        itemsVisibles = calcularItemsVisibles();
        
        if (totalItemsOriginales <= itemsVisibles) {
            carruselPista.style.transform = `translateX(0)`;
            detenerAutoPlay();
            return;
        }

        const itemAncho = itemsOriginales[0].offsetWidth; 
        const offset = indiceActual * itemAncho;

        carruselPista.style.transform = `translateX(-${offset}px)`;
        
        if (indiceActual >= totalItemsOriginales) {
            detenerAutoPlay();
            
            setTimeout(() => {
                aplicarTransicion(false);
                indiceActual = indiceActual - totalItemsOriginales;
                carruselPista.style.transform = `translateX(-${indiceActual * itemAncho}px)`;
                
                carruselPista.offsetHeight; 
                aplicarTransicion(true);
                
                iniciarCarruselAutomatico();
            }, 1000);
        }
    }
    
    function moverSiguiente() {
        indiceActual++;
        actualizarPosicion();
    }

    function iniciarCarruselAutomatico() {
        detenerAutoPlay(); 
        if (totalItemsOriginales > itemsVisibles) { 
            intervaloAutomatico = setInterval(moverSiguiente, 2000);
        }
    }

    function detenerAutoPlay() {
        clearInterval(intervaloAutomatico);
    }

    if (seccionEmpresas) { 
        seccionEmpresas.addEventListener('mouseenter', detenerAutoPlay);
        seccionEmpresas.addEventListener('mouseleave', iniciarCarruselAutomatico);
    }

    window.addEventListener('resize', () => {
        actualizarPosicion();
        iniciarCarruselAutomatico();
    });
    
    setTimeout(() => {
        aplicarTransicion(false);
        indiceActual = 0; 
        actualizarPosicion();
        aplicarTransicion(true);
        iniciarCarruselAutomatico();
    }, 100);
});