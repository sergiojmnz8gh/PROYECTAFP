class Validator {
    constructor() {
        this.errors = {};
        this.data = {};
    }

    setData(formData) {
        this.errors = {};
        this.data = formData;
    }

    requerido(campo, mensajeError = `El campo ${campo} es obligatorio.`) {
        if (!this.data[campo] || String(this.data[campo]).trim() === '') {
            this.addError(campo, mensajeError);
            return false;
        }
        return true;
    }

    enteroRango(campo, min, max, mensajeError = `Debe ser un número entero entre ${min} y ${max}.`) {
        const valor = parseInt(this.data[campo]);
        if (isNaN(valor) || valor < min || valor > max) {
            this.addError(campo, mensajeError);
            return false;
        }
        return true;
    }

    cadenaRango(campo, min, max, mensajeError = `Debe tener entre ${min} y ${max - 1} caracteres.`) {
        const valor = this.data[campo] ? String(this.data[campo]).length : 0;
        if (valor < min || valor >= max) {
            this.addError(campo, mensajeError);
            return false;
        }
        return true;
    }

    email(campo, mensajeError = `Debe ser un email válido.`) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.data[campo])) {
            this.addError(campo, mensajeError);
            return false;
        }
        return true;
    }

    dni(campo, mensajeError = `El campo ${campo} no es un DNI válido.`) {
        const dniRegex = /^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE]$/i;
        const valor = String(this.data[campo]).toUpperCase();

        if (!dniRegex.test(valor)) {
            this.addError(campo, mensajeError);
            return false;
        }

        const numero = parseInt(valor.substring(0, 8), 10);
        const letra = valor.substring(8, 9);
        const letras = "TRWAGMYFPDXBNJZSQVHLCKE";

        if (letras.charAt(numero % 23) === letra) {
            return true;
        } else {
            this.addError(campo, mensajeError);
            return false;
        }
    }

    patron(campo, patron, mensajeError = `El campo ${campo} no cumple el patrón requerido.`) {
        if (!patron.test(this.data[campo])) {
            this.addError(campo, mensajeError);
            return false;
        }
        return true;
    }

    validaConFuncion(campo, funcion, mensajeError) {
        if (!funcion(this.data[campo])) {
            this.addError(campo, mensajeError);
            return false;
        }
        return true;
    }

    addError(campo, mensaje) {
        if (!this.errors[campo]) {
            this.errors[campo] = mensaje;
        }
    }

    validacionPasada() {
        return Object.keys(this.errors).length === 0;
    }

    imprimirError(campo) {
        return this.errors[campo] ? `<span class="error_mensaje">${this.errors[campo]}</span>` : '';
    }

    getErrors() {
        return this.errors;
    }

    getValor(campo) {
        return this.data[campo] !== undefined ? this.data[campo] : null;
    }
}