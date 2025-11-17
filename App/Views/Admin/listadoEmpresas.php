    <?php $this->layout('layout', ['title' => 'Listado de Empresas | ProyectaFP']) ?>

    <?php $this->start('js') ?>
    <?php $this->stop() ?>

    <?php $this->start('contenidoPagina') ?>
        <section>
            <h1 class="page-title">Listado de Empresas</h1>
            
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
                            <th>Nombre <img class="icon" src="img/orden.png" alt=""></th>
                            <th>Contacto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empresas as $empresa): ?>
                            <tr>
                                <td><?= $empresa->id ?></td>
                                <td><?= $empresa->nombre ?></td>
                                <td><?= $empresa->telefono ?><br><?= $empresa->email ?></td>
                                <td class="div-actions">
                                    <form action="/index.php" method="GET">
                                        <input type="hidden" name="admin" value="fichaempresa">
                                        <input type="hidden" name="id" value="<?= $empresa->id ?>">
                                        <button type="submit" class="btn-ficha"><img src="<?= $empresa->logo ?>"></button>
                                    </form>
                                    <div class="div-actions-btns">
                                        <form action="/index.php?admin=editarempresa" method="POST">
                                            <input type="hidden" name="id" value="<?= $empresa->id ?>">
                                            <button type="submit" class="btn-action btn2">Editar</button>
                                        </form>
                                        <form action="/index.php?admin=borrarempresa" method="POST">
                                            <input type="hidden" name="id" value="<?= $empresa->id ?>">
                                            <button type="submit" class="btn-action btn1">Borrar</button>
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