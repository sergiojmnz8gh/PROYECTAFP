<?php $this->layout('layout', ['title' => 'Listado de Solicitudes | ProyectaFP']) ?>

<?php $this->start('js') ?>
    <script src="js/modal.js"></script>
    <script src="js/tabla.js"></script>
    <script src="js/csv.js"></script>
    <script src="js/listadoSolicitudes.js"></script>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section>
        <h1 class="page-title">Listado de Solicitudes</h1>
        
        <div class="search-controls">
            <div class="search-group">
                <input type="text" name="buscar" id="buscar" placeholder="Buscar alumno..." class="search-input">
                <button type="button" id="btnbuscar" class="btn btn2">Buscar</button> 
            </div>
            <div class="btn-crud-right"> 
                <button type="button" id="add" class="btn-action btn2">+ Añadir</button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="ordenable">ID <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Oferta <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Nombre Alumno <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Apellidos Alumno <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Fecha <img class="icon" src="img/orden.png" alt=""></th>
                        <th>CV visto</th>
                    </tr>
                </thead>
                <tbody id="alumnosTableBody">
                </tbody>
            </table>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>