document.addEventListener("DOMContentLoaded", function () {
    const tbody = document.querySelectorAll("tbody")[0];
    const btnAdd = document.getElementById("add");
    const btnMassAdd = document.getElementById("massAdd");

    // ========================================================================================
    // !!! IMPORTANTE: AJUSTA LAS URLS DE TU API AQUI !!!
    // ========================================================================================
    const API_BASE_URL = '/index.php?api=alumnos'; 
    const API_FAMILIAS_URL = '/index.php?api=familias';
    const API_CICLOS_URL = '/index.php?api=ciclos';
    // ========================================================================================

    // Función para recargar y pintar la tabla desde la API
    async function fetchAndRenderAlumnos() {
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
            console.error('Error al obtener alumnos:', error);
            tbody.innerHTML = '<tr><td colspan="5">Error al cargar los alumnos. Por favor, intente de nuevo más tarde.</td></tr>';
        }
    }

    // ========================================================================================
    // Nuevas funciones para cargar Familias y Ciclos
    // ========================================================================================
    async function fetchAndPopulateFamilias(selectElementId, selectedValue = null) {
        const select = document.getElementById(selectElementId);
        if (!select) {
            console.warn(`Select con ID '${selectElementId}' no encontrado.`);
            return;
        }

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
                console.error('Error fetching familias:', result.message);
            }
        } catch (error) {
            select.innerHTML = '<option value="">Error de conexión</option>';
            console.error('Network error fetching familias:', error);
        }
    }

    async function fetchAndPopulateCiclos(selectElementId, familiaId = null, selectedValue = null) {
        const select = document.getElementById(selectElementId);
        if (!select) {
            console.warn(`Select con ID '${selectElementId}' no encontrado.`);
            return;
        }

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
                    select.innerHTML = '<option value="">No hay ciclos para esta familia</option>';
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
                console.error('Error fetching ciclos:', result.message);
            }
        } catch (error) {
            select.innerHTML = '<option value="">Error de conexión</option>';
            console.error('Network error fetching ciclos:', error);
        }
    }
    // ========================================================================================

    fetchAndRenderAlumnos();

    function pintarTabla(alumnosJson) {
        tbody.innerHTML = "";

        if (!alumnosJson || !Array.isArray(alumnosJson) || alumnosJson.length === 0) {
            console.warn("No se recibieron datos de alumnos o el formato es inválido.");
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

            c1.innerHTML = alumno.id;
            c2.innerHTML = alumno.nombre;
            c3.innerHTML = alumno.apellidos;
            c4.innerHTML = alumno.email;

            let btnFicha = document.createElement("img");
            btnFicha.src = alumno.foto || '/img/default_avatar.png';
            btnFicha.classList.add("btn-ficha");

            let btnEdit = document.createElement("button");
            btnEdit.textContent = "Editar";
            btnEdit.classList.add("btn-action", "btn2");

            let btnBorrar = document.createElement("button");
            btnBorrar.textContent = "Borrar";
            btnBorrar.classList.add("btn-action", "btn1");

            let divAcciones = document.createElement("div");
            divAcciones.appendChild(btnEdit);
            divAcciones.appendChild(btnBorrar);

            c5.appendChild(btnFicha);
            c5.appendChild(divAcciones);
            c5.classList.add("div-actions");

            btnFicha.addEventListener("click", function () {
                let div = `
                    <h1 class="modal-title">Ficha de Alumno</h1>
                    <div class="div-ficha">
                        <img src="${alumno.foto || '/img/default_avatar.png'}">
                        <p><strong>ID:</strong> ${alumno.id}</p>
                        <p><strong>Nombre:</strong> ${alumno.nombre}</p>
                        <p><strong>Apellidos:</strong> ${alumno.apellidos}</p>
                        <p><strong>Email:</strong> ${alumno.email}</p>
                        <p><strong>Teléfono:</strong> ${alumno.telefono || 'N/A'}</p>
                        <p><strong>Dirección:</strong> ${alumno.direccion || 'N/A'}</p>
                        <p><strong>Familia Profesional:</strong> ${alumno.familia_nombre || 'N/A'}</p>
                        <p><strong>Ciclo Formativo:</strong> ${alumno.ciclo_nombre || 'N/A'}</p>
                    </div>
                `;
                new Modal().modalDiv(div);
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
                        <label for="editDireccion">Dirección:</label>
                        <input type="text" name="direccion" id="editDireccion" value="${alumno.direccion || ''}">
                    </div>
                    <div class="form-group">
                        <label for="editTelefono">Teléfono:</label>
                        <input type="text" name="telefono" id="editTelefono" value="${alumno.telefono || ''}">
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
                let modal = new Modal();
                modal.modalDiv(div);

                // --- Cargar los selects después de que el modal se muestre ---
                // Nota: Asegúrate de que tu objeto alumno tenga las propiedades familia_id y ciclo_id.
                // Si no las tiene, tendrás que modificar tu API de alumnos para que las devuelva.
                fetchAndPopulateFamilias('familia', alumno.familia_id); 
                fetchAndPopulateCiclos('ciclo', alumno.familia_id, alumno.ciclo_id); 

                // Opcional: Hacer que los ciclos dependan de la familia seleccionada
                document.getElementById('familia').addEventListener('change', (event) => {
                    const selectedFamiliaId = event.target.value;
                    if (selectedFamiliaId) {
                        fetchAndPopulateCiclos('ciclo', selectedFamiliaId);
                    } else {
                        document.getElementById('ciclo').innerHTML = '<option value="">Selecciona una familia primero</option>';
                    }
                });
                // -----------------------------------------------------------

                document.getElementById('editAlumnoForm').addEventListener('submit', async function (e) {
                    e.preventDefault(); 
                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());

                    try {
                        // Si 'familia_id' y 'ciclo_id' se envían como parte de 'data'
                        // Asegúrate de que el backend los espera.
                        const response = await fetch(API_BASE_URL, { 
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data)
                        });

                        const result = await response.json();
                        if (result.success) {
                            modal.cerrarModal();
                            alert('Alumno actualizado correctamente.');
                            fetchAndRenderAlumnos();
                        } else {
                            alert('Error al actualizar el alumno: ' + (result.message || 'Error desconocido.'));
                        }
                    } catch (error) {
                        console.error('Error al enviar datos de edición:', error);
                        alert('Error de conexión al actualizar el alumno.');
                    }
                });
            });

            btnBorrar.addEventListener("click", async function () {
                const confirmar = await new Modal().modalConfirmacion("¿Estás seguro que quieres borrar este alumno?");
                if (confirmar) {
                    try {
                        const response = await fetch(API_BASE_URL, { 
                            method: 'DELETE',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ id: alumno.id })
                        });

                        const result = await response.json();
                        if (result.success) {
                            alert('Alumno eliminado correctamente.');
                            fetchAndRenderAlumnos();
                        } else {
                            alert('Error al eliminar el alumno: ' + (result.message || 'Error desconocido.'));
                        }
                    } catch (error) {
                        console.error('Error al eliminar alumno:', error);
                        alert('Error de conexión al eliminar el alumno.');
                    }
                }
            });

            fila.appendChild(c1);
            fila.appendChild(c2);
            fila.appendChild(c3);
            fila.appendChild(c4);
            fila.appendChild(c5);
            tbody.appendChild(fila);
        });

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
            let modal = new Modal();
            modal.modalDiv(div);

            // --- Cargar los selects para el formulario de Añadir ---
            fetchAndPopulateFamilias('addFamilia'); // ID del select para añadir
            fetchAndPopulateCiclos('addCiclo');    // ID del select para añadir
            
            // Opcional: Hacer que los ciclos dependan de la familia seleccionada en el formulario de añadir
            document.getElementById('addFamilia').addEventListener('change', (event) => {
                const selectedFamiliaId = event.target.value;
                if (selectedFamiliaId) {
                    fetchAndPopulateCiclos('addCiclo', selectedFamiliaId);
                } else {
                    document.getElementById('addCiclo').innerHTML = '<option value="">Selecciona una familia primero</option>';
                }
            });
            // -----------------------------------------------------------

            document.getElementById('addAlumnoForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                data.rol_id = 3; 
                
                try {
                    const response = await fetch(API_BASE_URL, { 
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (result.success) {
                        modal.cerrarModal();
                        alert('Alumno añadido correctamente.');
                        fetchAndRenderAlumnos();
                    } else {
                        alert('Error al añadir el alumno: ' + (result.message || 'Error desconocido.'));
                    }
                } catch (error) {
                    console.error('Error al enviar datos de alumno:', error);
                    alert('Error de conexión al añadir el alumno.');
                }
            });
        });
        
        // ========================================================================================
        // Funcionalidad de Carga Masiva
        // ========================================================================================
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
                <div class="modal-confirm-buttons">
                <input type="submit" class="btn-primary" value="Subir Archivo">
                </div>
                </form>
                <table>
                    <thead>
                        <tr>
                            <th>Selección</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                        </tr>
                    <tbody id="tabla"></tbody>
                </table>
                `;
                let modal = new Modal(); // Almacena la instancia para cerrar después
                modal.modalDiv(div);
                const tabla = document.getElementById('tabla');
                const fichero = document.getElementById('fichero');

                // --- Cargar los selects para el formulario de Carga Masiva ---
                fetchAndPopulateFamilias('massFamilia'); 
                fetchAndPopulateCiclos('massCiclo');    
                
                // Opcional: Hacer que los ciclos dependan de la familia seleccionada en carga masiva
                document.getElementById('massFamilia').addEventListener('change', (event) => {
                    const selectedFamiliaId = event.target.value;
                    if (selectedFamiliaId) {
                        fetchAndPopulateCiclos('massCiclo', selectedFamiliaId);
                    } else {
                        document.getElementById('massCiclo').innerHTML = '<option value="">Selecciona una familia primero</option>';
                    }
                });
                // -----------------------------------------------------------

                fichero.onchange = function() {
                    if (this.files[0].type!='text/csv') {
                        alert('Por favor, introduce un archivo CSV.');
                    }
                    else {
                        const lector = new FileReader();
                        
                        lector.onload = function() {
                            const filas=this.result.split('\n');
                            let tamFilas=filas.length;
                            tabla.innerHTML='';
                            for (let i=0;i<tamFilas;i++) {
                                let tr=document.createElement('tr');
                                let celdas=filas[i].split(';');
                                let tamCeldas=celdas.length;
                                // Ajuste para el checkbox si no existe un valor celdas[j] para él
                                let tdCheckbox = document.createElement('td');
                                tdCheckbox.innerHTML = `<input type="checkbox" name="seleccion" value="${celdas.join(';')}" checked>`; // Se puede seleccionar la fila completa
                                tr.appendChild(tdCheckbox);

                                for (let j=0; j<tamCeldas; j++) { // Empieza en j=0 para los datos
                                    let td=document.createElement('td');
                                    td.innerHTML=celdas[j];
                                    tr.appendChild(td);
                                }
                                tabla.appendChild(tr);
                            }
                        }
                        lector.readAsText(this.files[0]);
                    }
                }
                
                // Aquí podrías añadir un submit listener para massAddForm si procesas el CSV con AJAX
                // document.getElementById('massAddForm').addEventListener('submit', async function(e) { /* ... */ });

            });
        }

        // ========================================================================================
        // Funcionalidad de Búsqueda (Actualización en la tabla actual)
        // ========================================================================================
        const btnBuscar = document.getElementById("btnbuscar");
        if (btnBuscar) {
            btnBuscar.addEventListener("click", function() {
                let buscar = document.getElementById("buscar").value.toLowerCase();
                let filas = document.querySelectorAll("tbody tr");

                filas.forEach(fila => {
                    let tds = fila.querySelectorAll("td");
                    // Asegúrate de que los índices de celda son correctos para la búsqueda
                    let textoFila = `${tds[1].innerHTML} ${tds[2].innerHTML} ${tds[3].innerHTML}`.toLowerCase();
                    
                    if (textoFila.includes(buscar)) {
                        fila.style.display = "";
                    } else {
                        fila.style.display = "none";
                    }
                });
            });
        }
    }
});