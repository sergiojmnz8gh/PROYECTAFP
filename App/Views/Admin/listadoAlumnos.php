<?php $this->layout('layout', ['title' => 'Listado de Alumnos | ProyectaFP']) ?>

<?php $this->start('js') ?>
    <script src="js/modal.js"></script>
    <script src="js/tabla.js"></script>
    <script src="js/csv.js"></script>
    <script src="js/listadoAlumnos.js"></script>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section>
        <h1 class="page-title">Listado de Alumnos</h1>
        
        <div class="search-controls">
            <div class="search-group">
                <input type="text" name="buscar" id="buscar" placeholder="Buscar alumno..." class="search-input">
                <button type="button" id="btnbuscar" class="btn btn2">Buscar</button> 
            </div>
            <div class="filter-group">
                <label for="filterCiclo">Filtrar por Ciclo:</label>
                <select name="familia_id" id="filterFamilia" class="filter-input">
                    <option value="">Selecciona una familia</option>
                </select>
                <select name="ciclo_id" id="filterCiclo" class="filter-input">
                    <option value="">Selecciona un ciclo</option>
                </select>
            </div>
            <div class="btn-crud-right"> 
                <button type="button" id="add" class="btn-action btn2">+ Añadir</button>
                <button type="button" id="massAdd" class="btn-small btn1">++</button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="ordenable">ID <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Nombre <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Apellidos <img class="icon" src="img/orden.png" alt=""></th>
                        <th>Ciclo Formativo</th>
                        <th>Contacto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="alumnosTableBody">
                </tbody>
            </table>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>