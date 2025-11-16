class Modal {
    constructor() {
        this.velo = null;
        this.modal = null;
        this.btnCerrar = null;
        this.content = null;
    }

    crearElementos() {
        if (this.velo && document.body.contains(this.velo)) {
            return;
        }

        this.velo = document.createElement('div');
        this.velo.className = 'velo';

        this.modal = document.createElement('div');
        this.modal.className = 'modal';

        this.btnCerrar = document.createElement('button');
        this.btnCerrar.className = 'btnModal';
        this.btnCerrar.innerHTML = 'X';
        this.btnCerrar.onclick = () => this.cerrarModal();

        this.content = document.createElement('div');
        this.content.className = 'contentModal';

        this.modal.appendChild(this.btnCerrar);
        this.modal.appendChild(this.content);
        this.velo.appendChild(this.modal);
        document.body.appendChild(this.velo);

        this.velo.addEventListener('click', (e) => {
            if (e.target === this.velo) {
                this.cerrarModal();
            }
        });
    }

    abrirModal() {
        this.crearElementos();
        this.velo.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    cerrarModal() {
        if (this.velo && document.body.contains(this.velo)) {
            document.body.removeChild(this.velo);
        }
        document.body.style.overflow = '';
    }

    modalConfirmacion(mensaje) {
        return new Promise((resolve) => {
            this.abrirModal();
            this.content.innerHTML = `
                <h1 class="modal-title">${mensaje}</h1>
                <div class="modal-confirm-buttons">
                    <button class="btn btn2" id="modalConfirmYes">Sí</button>
                    <button class="btn btn1" id="modalConfirmNo">No</button>
                </div>
            `;
            document.getElementById('modalConfirmYes').onclick = () => {
                this.cerrarModal();
                resolve(true);
            };
            document.getElementById('modalConfirmNo').onclick = () => {
                this.cerrarModal();
                resolve(false);
            };
        });
    }

    modalOk(mensaje) {
        return new Promise((resolve) => {
            this.abrirModal();
            this.content.innerHTML = `
                    <h1 class="modal-title">${mensaje}</h1>
                    <div class="modal-confirm-buttons">
                        <button class="btn btn2" id="modalOk">Ok</button>
                    </div>
                `;
            document.getElementById('modalOk').onclick = () => {
                this.cerrarModal();
                resolve(true);
            };
        });
    }

    modalDiv(div) {
        this.abrirModal();
        this.content.innerHTML = div;
    }
}