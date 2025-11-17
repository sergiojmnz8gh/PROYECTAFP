    <?php $this->layout('layout', ['title' => 'Listado de Ofertas | ProyectaFP']) ?>

    <?php $this->start('js') ?>
    <?php $this->stop() ?>

    <?php $this->start('contenidoPagina') ?>
        <section>
            <h1 class="page-title">Listado de Ofertas</h1>
            
            <div class="search-controls">
                <div class="search_group">
                <form action="index.php" method="GET">
                    <input type="hidden" name="admin" value="empresas">
                    <input type="text" name="buscarEmpresa" id="buscarEmpresa" placeholder="Buscar empresa..." class="search-input" value="<?= $_GET['buscarEmpresa'] ?? '' ?>">
                    <button type="submit" id="btnBuscarEmpresa" class="btn btn2">Buscar</button>
                </form>
                </div> 
                <div class="btn-crud-right"> 
                    <form action="/index.php?admin=invitarempresa" method="POST">
                        <button type="submit" id="add" class="btn-action btn2">+ Añadir</button>
                    </form>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Título <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Descripción</th>
                            <th>Empresa <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Ciclo <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Fecha Inicio <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Fecha Fin <img class="icon" src="img/orden.png" alt=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ofertas as $oferta): ?>
                            <tr>
                                <td><?= $oferta->id ?></td>
                                <td><?= $oferta->titulo ?></td>
                                <td><?= $oferta->descripcion ?></td>
                                <td><?= $oferta->empresa_nombre ?></td>
                                <td><?= $oferta->ciclo_nombre ?></td>
                                <td><?= $oferta->fecha_inicio ?></td>
                                <td><?= $oferta->fecha_fin ?></td>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </section>

    <?php $this->stop('contenidoPagina') ?>