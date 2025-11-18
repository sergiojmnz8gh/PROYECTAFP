    <?php $this->layout('layout', ['title' => 'Listado de Ofertas | ProyectaFP']) ?>

    <?php $this->start('js') ?>
    <script src="js/tabla.js"></script>
    <?php $this->stop() ?>

    <?php $this->start('contenidoPagina') ?>
        <section>
            <h1 class="page-title">Listado de Ofertas</h1>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="ordenable">ID <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Título <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Descripción</th>
                            <th>Empresa <img class="icon" src="img/orden.png" alt=""></th>
                            <th class="ordenable">Fecha Inicio <img class="icon" src="img/orden.png" alt=""></th>
                            <th class="ordenable">Fecha Fin <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ofertas as $oferta): ?>
                            <tr>
                                <td><?= $oferta->id ?></td>
                                <td><?= $oferta->titulo ?></td>
                                <td><?= $oferta->descripcion ?></td>
                                <td><?= $oferta->empresa_nombre ?></td>
                                <td><?= $oferta->fecha_inicio ?></td>
                                <td><?= $oferta->fecha_fin ?></td>
                                <td class="div-actions">
                                    <div class="div-actions-btns">
                                        <form action="/index.php?page=inscribirseoferta" method="POST">
                                            <button type="submit" class="btn-action btn2">Inscribirse</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </section>

    <?php $this->stop('contenidoPagina') ?>