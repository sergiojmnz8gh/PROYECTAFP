document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.querySelectorAll("tbody")[0];
    const btnAdd = document.getElementById("add");
    const btnMassAdd = document.getElementById("massAdd");
    const modal = new Modal();

    const API_BASE_URL = '/index.php?api=alumnos';
    const API_FAMILIAS_URL = '/index.php?api=familias';
    const API_CICLOS_URL = '/index.php?api=ciclos';

    async function fetchAlumnos() {
        try {
            const response = await fetch(API_BASE_URL, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
            }

            const alumnosJson = await response.json();
            pintarTabla(alumnosJson);
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="5">Error al cargar los alumnos. Por favor, intente de nuevo más tarde.</td></tr>';
        }
    }

    async function fetchFamilias(selectElementId, selectedValue = null) {
        const select = document.getElementById(selectElementId);
        if (!select) return;

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
        if (!select) return;

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
                    select.innerHTML = '<option value="" hidden>Selecciona un ciclo</option>';
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

    fetchAlumnos();

    function pintarTabla(alumnosJson) {
        tbody.innerHTML = "";

        if (!alumnosJson || !Array.isArray(alumnosJson) || alumnosJson.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5">No hay alumnos para mostrar.</td></tr>';
            return;
        }

        alumnosJson.forEach(alumno => {
            let fila = document.createElement("tr");
            let c1 = document.createElement("td");
            let c2 = document.createElement("td");
            let c3 = document.createElement("td");
            let c4 = document.createElement("td");
            let c5 = document.createElement("td");
            let c6 = document.createElement("td");

            c1.innerHTML = alumno.id;
            c2.innerHTML = alumno.nombre;
            c3.innerHTML = alumno.apellidos;
            c4.innerHTML = alumno.ciclo;
            c5.innerHTML = alumno.telefono+'<br>'+alumno.email;

            let btnFicha = document.createElement("img");
            btnFicha.src = alumno.foto ? alumno.foto : '/img/avatar.jpg';
            btnFicha.classList.add("btn-ficha");

            let btnEdit = document.createElement("button");
            btnEdit.textContent = "Editar";
            btnEdit.classList.add("btn-action", "btn2");

            let btnBorrar = document.createElement("button");
            btnBorrar.textContent = "Borrar";
            btnBorrar.classList.add("btn-action", "btn1");

            let divAcciones = document.createElement("div");
            divAcciones.classList.add("div-actions-btns");
            divAcciones.appendChild(btnEdit);
            divAcciones.appendChild(btnBorrar);

            c6.appendChild(btnFicha);
            c6.appendChild(divAcciones);
            c6.classList.add("div-actions");

            btnFicha.addEventListener("click", function () {
                let div = `
                    <h1 class="modal-title">Ficha de Alumno</h1>
                    <div class="div-ficha">
                        <img src="${alumno.foto || '/img/avatar.jpg'}">
                        <p><strong>Nombre:</strong> ${alumno.nombre}</p>
                        <p><strong>Apellidos:</strong> ${alumno.apellidos}</p>
                        <p><strong>Teléfono:</strong> ${alumno.telefono || 'N/A'}</p>
                        <p><strong>Email:</strong> ${alumno.email}</p>
                        <p><strong>Dirección:</strong> ${alumno.direccion || 'N/A'}</p>
                        <p><strong>Ciclo Formativo:</strong> ${alumno.ciclo || 'N/A'}</p>
                    </div>
                `;
                modal.modalDiv(div);
            });

            btnEdit.addEventListener("click", function () {
                let div = `
                <form id="editAlumnoForm">
                    <h1>Edición de Alumno</h1>
                    <input type="hidden" name="id" value="${alumno.id}">
                    <div class="form-group">
                        <label for="editEmail">Email:</label>
                        <input type="email" name="email" id="editEmail" value="${alumno.email}" required>
                    </div>
                    <div class="form-group">
                        <label for="editNombre">Nombre:</label>
                        <input type="text" name="nombre" id="editNombre" value="${alumno.nombre}" required>
                    </div>
                    <div class="form-group">
                        <label for="editApellidos">Apellidos:</label>
                        <input type="text" name="apellidos" id="editApellidos" value="${alumno.apellidos}" required>
                    </div>
                    <div class="form-group">
                        <label for="editTelefono">Teléfono:</label>
                        <input type="text" name="telefono" id="editTelefono" value="${alumno.telefono || ''}">
                    </div>
                    <div class="form-group">
                        <label for="editDireccion">Dirección:</label>
                        <input type="text" name="direccion" id="editDireccion" value="${alumno.direccion || ''}">
                    </div>
                    <div class="form-group">
                        <label for="familia">Familia Profesional:</label>
                        <select name="familia_id" id="familia" required>
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="ciclo">Ciclo Formativo:</label>
                        <select name="ciclo_id" id="ciclo" required>
                            </select>
                    </div>
                    <input type="submit" value="Guardar Cambios" class="btn-primary">
                </form>
                `;
                modal.modalDiv(div);

                fetchFamilias('familia', alumno.familia_id);
                fetchCiclos('ciclo', alumno.familia_id, alumno.ciclo_id);

                document.getElementById('familia').addEventListener('change', (event) => {
                    const selectedFamiliaId = event.target.value;
                    if (selectedFamiliaId) {
                        fetchCiclos('ciclo', selectedFamiliaId);
                    } else {
                        document.getElementById('ciclo').innerHTML = '<option value="">Selecciona un ciclo</option>';
                    }
                });

                document.getElementById('editAlumnoForm').addEventListener('submit', async function (e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());

                    const response = await fetch(API_BASE_URL, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (result.success) {
                        modal.cerrarModal();
                        fetchAlumnos();
                    }
                });
            });

            btnBorrar.addEventListener("click", async function () {
                const confirmar = await modal.modalConfirmacion("¿Estás seguro que quieres borrar este alumno?");
                if (confirmar) {
                    const response = await fetch(API_BASE_URL, {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: alumno.id })
                    });

                    const result = await response.json();
                    if (result.success) {
                        fetchAlumnos();
                    }
                }
            });

            fila.appendChild(c1);
            fila.appendChild(c2);
            fila.appendChild(c3);
            fila.appendChild(c4);
            fila.appendChild(c5);
            fila.appendChild(c6);
            tbody.appendChild(fila);
        });
    }

    btnAdd.addEventListener("click", function () {
        let div = `
                <form id="addAlumnoForm">
                    <h1>Invitación de Alumno</h1>
                    <div class="form-group">
                        <label for="addEmail">Email:</label>
                        <input type="email" name="email" id="addEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="addNombre">Nombre:</label>
                        <input type="text" name="nombre" id="addNombre" required>
                    </div>
                    <div class="form-group">
                        <label for="addApellidos">Apellidos:</label>
                        <input type="text" name="apellidos" id="addApellidos" required>
                    </div>
                    <div class="form-group">
                        <label for="familia">Familia Profesional:</label>
                        <select name="familia_id" id="addFamilia" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ciclo">Ciclo Formativo:</label>
                        <select name="ciclo_id" id="addCiclo" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addDireccion">Dirección:</label>
                        <input type="text" name="direccion" id="addDireccion">
                    </div>
                    <div class="form-group">
                        <label for="addTelefono">Teléfono:</label>
                        <input type="text" name="telefono" id="addTelefono">
                    </div>
                    <input type="submit" value="Registrar" class="btn-primary">
                </form>
                `;
        modal.modalDiv(div);

        fetchFamilias('addFamilia');
        document.getElementById('addCiclo').innerHTML = '<option value="">Selecciona un ciclo</option>';
        document.getElementById('addCiclo').disabled = true;

        document.getElementById('addFamilia').addEventListener('change', (event) => {
            const selectedFamiliaId = event.target.value;
            if (selectedFamiliaId) {
                fetchCiclos('addCiclo', selectedFamiliaId);
                document.getElementById('addCiclo').disabled = false;
            } else {
                document.getElementById('addCiclo').innerHTML = '<option value="">Selecciona un ciclo</option>';
                document.getElementById('addCiclo').disabled = true;
            }
        });

        document.getElementById('addAlumnoForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            data.rol_id = 3;

            const response = await fetch(API_BASE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (result.success) {
                modal.cerrarModal();
                fetchAlumnos();
            }
        });
    });

    if (btnMassAdd) {
        btnMassAdd.addEventListener("click", function () {
            let div = `<form id="massAddForm" action="" method="post" enctype="multipart/form-data">
                <h1 class="modal-title">Invitación Masiva de Alumnos</h1>
                <div class="form-group">
                <label for="archivo">Archivo CSV: </label>
                <input type="file" name="archivo" id="fichero" class="form-control" accept=".csv" required>
                </div>
                <a href="/csv/modelo.csv"><u>modelo.csv</u></a>
                <div class="form-group">
                <label for="familia">Familia Profesional:</label>
                <select name="familia_id" id="massFamilia" required>
                    </select>
                <label for="ciclo">Ciclo Formativo:</label>
                <select name="ciclo_id" id="massCiclo" required>
                    </select>
                </div>
                <table class="csv-preview-table">
                    <thead>
                        <tr>
                            <th>Selección</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Contacto</th>
                        </tr>
                    </thead>
                    <tbody id="csv-preview-tbody"></tbody> </table>
                <div class="modal-confirm-buttons">
                <input type="submit" class="btn-primary" value="Invitar alumnos">
                </div>
                </form>
                `;
            modal.modalDiv(div);
            const csvPreviewTbody = document.getElementById('csv-preview-tbody');
            const fichero = document.getElementById('fichero');

            fetchFamilias('massFamilia');
            document.getElementById('massCiclo').innerHTML = '<option value="">Selecciona un ciclo</option>';
            document.getElementById('massCiclo').disabled = true;

            document.getElementById('massFamilia').addEventListener('change', (event) => {
                const selectedFamiliaId = event.target.value;
                if (selectedFamiliaId) {
                    fetchCiclos('massCiclo', selectedFamiliaId);
                    document.getElementById('massCiclo').disabled = false;
                } else {
                    document.getElementById('massCiclo').innerHTML = '<option value="">Selecciona un ciclo</option>';
                    document.getElementById('massCiclo').disabled = true;
                }
            });
            // -----------------------------------------------------------

            fichero.onchange = function () {
                if (this.files.length > 0 && this.files[0].type == 'text/csv') {
                    const lector = new FileReader();

                    lector.onload = function () {
                        const filas = this.result.split('\n').filter(line => line.trim() !== '');
                        csvPreviewTbody.innerHTML = '';
                        filas.forEach(filaTexto => {
                            const celdas = filaTexto.split(';');
                            if (celdas.length >= 3) {
                                let tr = document.createElement('tr');
                                let tdCheckbox = document.createElement('td');
                                tdCheckbox.innerHTML = `<input type="checkbox" class="checkbox-input" name="seleccion" value="${celdas.join(';')}" checked>`;
                                tr.appendChild(tdCheckbox);

                                for (let j = 0; j < 2; j++) {
                                    let td = document.createElement('td');
                                    td.innerHTML = celdas[j] ? celdas[j].trim() : '';
                                    tr.appendChild(td);
                                }
                                let td = document.createElement('td');
                                td.innerHTML = celdas[2]+'<br>'+celdas[3];
                                tr.appendChild(td);

                                csvPreviewTbody.appendChild(tr);
                            }
                        });
                    }
                    lector.readAsText(this.files[0]);
                } else {
                    csvPreviewTbody.innerHTML = '';
                }
            }

            document.getElementById('massAddForm').addEventListener('submit', async function (e) {
                e.preventDefault();

                const selectedCheckboxes = document.querySelectorAll('#csv-preview-tbody input[name="seleccion"]:checked');
                if (selectedCheckboxes.length === 0) {
                    modal.modalOk("Por favor, selecciona al menos un alumno para invitar.");
                    return;
                }

                const familiaId = document.getElementById('massFamilia').value;
                const cicloId = document.getElementById('massCiclo').value;

                if (!familiaId || !cicloId) {
                    modal.modalOk("Por favor, selecciona una Familia Profesional y un Ciclo Formativo.");
                    return;
                }

                const alumnosToAdd = [];
                selectedCheckboxes.forEach(checkbox => {
                    const rowData = checkbox.value.split(';');
                    alumnosToAdd.push({
                        nombre: rowData[0] ? rowData[0].trim() : '',
                        apellidos: rowData[1] ? rowData[1].trim() : '',
                        telefono: rowData[2] ? rowData[2].trim() : '',
                        email: rowData[3] ? rowData[3].trim() : '',
                        direccion: rowData[4] ? rowData[4].trim() : '',
                        familia_id: familiaId,
                        ciclo_id: cicloId,
                        rol_id: 3
                    });
                });

                try {
                    const response = await fetch(`${API_BASE_URL}/mass`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(alumnosToAdd)
                    });

                    const result = await response.json();
                    if (result.success) {
                        modal.cerrarModal();
                        modal.modalOk("Alumnos invitados correctamente.");
                        fetchAlumnos();
                    } else {
                        modal.modalOk(`Error al invitar alumnos: ${result.message || 'Error desconocido'}`);
                    }
                } catch (error) {
                    modal.modalOk("Error de conexión al intentar invitar alumnos.");
                }
            });
        });
    }

    const btnBuscar = document.getElementById("btnbuscar");
    if (btnBuscar) {
        const inputBuscar = document.getElementById("buscar");

        inputBuscar.addEventListener("keyup", function() {
            let buscar = inputBuscar.value.toLowerCase();
            let filas = tbody.querySelectorAll("tr");

            filas.forEach(fila => {
                let tds = fila.querySelectorAll("td");
                let textoFila = '';
                if (tds[1]) textoFila += tds[1].innerHTML;
                if (tds[2]) textoFila += ` ${tds[2].innerHTML}`;
                textoFila = textoFila.toLowerCase();

                if (textoFila.includes(buscar)) {
                    fila.style.display = "";
                } else {
                    fila.style.display = "none";
                }
            });
        });
    }

    fetchFamilias('filterFamilia');
    document.getElementById('filterCiclo').disabled = true;

    document.getElementById('filterFamilia').addEventListener('change', (event) => {
        const selectedFamiliaId = event.target.value;
        if (selectedFamiliaId) {
            fetchCiclos('filterCiclo', selectedFamiliaId);
            document.getElementById('filterCiclo').disabled = false;
        } else {
            document.getElementById('filterCiclo').innerHTML = '<option value="">Selecciona un ciclo</option>';
            document.getElementById('filterCiclo').disabled = true;
        }
    });

    filterCiclo.onchange = function() {
        const ciclo = document.getElementById('filterCiclo').options[document.getElementById('filterCiclo').selectedIndex].textContent;
        let filas = tbody.querySelectorAll("tr");

        filas.forEach(fila => {
            let tds = fila.querySelectorAll("td");
            if (tds[3].textContent == ciclo || ciclo == "Selecciona un ciclo") {
                fila.style.display = "";
            } else {
                fila.style.display = "none";
            }
        });
    }
    
});
