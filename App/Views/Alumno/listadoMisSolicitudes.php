<?php $this->layout('layout', ['title' => 'Mis Solicitudes | ProyectaFP']) ?>

<?php $this->start('js') ?>
    <script src="js/tabla.js"></script>
    <script src="js/listadoSolicitudesAlumno.js"></script>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section>
        <h1 class="page-title">Mis Solicitudes</h1>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="ordenable">ID <img class="icon" src="img/orden.png" alt=""></th>
                        <th>Oferta </th>
                        <th class="ordenable">Fecha <img class="icon" src="img/orden.png" alt=""></th>
                        <th>CV visto</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>