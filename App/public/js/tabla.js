window.addEventListener("load", function() {

    HTMLTableElement.prototype.ordenar = function(config = { columna: 0, tipo: "text", orden: 1 }) {
        let trs = Array.from(this.tBodies[0].rows);

        function comparar(a, b) {
            let resultado;
            const celdaA = a.cells[config.columna].innerText.trim();
            const celdaB = b.cells[config.columna].innerText.trim();

            switch (config.tipo) {
                case "num":
                    resultado = parseFloat(celdaA) - parseFloat(celdaB);
                    break;
                case "date":
                    resultado = celdaA.localeCompare(celdaB);
                    break;
                case "text":
                default:
                    resultado = celdaA.localeCompare(celdaB);
                    break;
            }
            return config.orden * resultado;
        }

        let ordenados = trs.sort(comparar);

        let tam = ordenados.length;
        for (let i = 0; i < tam; i++) {
            this.tBodies[0].appendChild(ordenados[i]);
        }
    }
    const ths = document.querySelectorAll("th.ordenable");
    let tam = ths.length;

    for (let i = 0; i < tam; i++) {
        ths[i].dataset.orden = 1; 

        ths[i].onclick = function() {
            const table = this.closest('table');
            let ordenActual = parseInt(this.dataset.orden);

            let nuevoOrden = ordenActual * -1;
            this.dataset.orden = nuevoOrden;

            table.ordenar({
                columna: this.cellIndex,
                orden: nuevoOrden
            });
        }
    }
});