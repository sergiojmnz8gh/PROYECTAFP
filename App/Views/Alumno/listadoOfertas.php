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
                            <th>Empresa <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Título <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Descripción</th>
                            <th class="ordenable">Fecha Inicio <img class="icon" src="img/orden.png" alt=""></th>
                            <th class="ordenable">Fecha Fin <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($ofertas){
                        foreach ($ofertas as $oferta): ?>
                            <tr>
                                <td><?= $oferta->id ?></td>
                                <td><?= $oferta->empresa_nombre ?></td>
                                <td><?= $oferta->titulo ?></td>
                                <td><?= $oferta->descripcion ?></td>
                                <td><?= date('d/m/Y', strtotime($oferta->fecha_inicio)) ?></td>
                                <td><?= date('d/m/Y', strtotime($oferta->fecha_fin)) ?></td>
                                <td class="div-actions">
                                    <div class="div-actions-btns">
                                        <form action="/index.php?page=inscribirseoferta" method="POST">
                                            <input type="hidden" name="oferta_id" value="<?= $oferta->id ?>">
                                            <button type="submit" id="inscribirse" class="btn2">Inscribirse</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;
                        } else {
                            echo '<tr><td colspan="7">No hay ofertas para mostrar.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

    <?php $this->stop('contenidoPagina') ?>