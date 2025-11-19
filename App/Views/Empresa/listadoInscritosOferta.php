<?php $this->layout('layout', ['title' => 'Solicitudes | ProyectaFP']) ?>

<?php $this->start('js') ?>
    <script src="js/tabla.js"></script>
    <script src="js/listadoSolicitudesOferta.js"></script>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section>
        <h1 id="titulo" class="page-title">Solicitudes de la oferta: </h1>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="ordenable">ID <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Nombre <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Apellidos <img class="icon" src="img/orden.png" alt=""></th>
                        <th class="ordenable">Fecha <img class="icon" src="img/orden.png" alt=""></th>
                        <th>Ver CV</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>