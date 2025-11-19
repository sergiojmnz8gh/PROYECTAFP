    <?php $this->layout('layout', ['title' => 'Listado de Empresas | ProyectaFP']) ?>

    <?php $this->start('js') ?>
    <?php $this->stop() ?>

    <?php $this->start('contenidoPagina') ?>
        <section>
            <h1 class="page-title">Listado de Empresas</h1>
            
            <div class="search-controls">
                <div class="search_group">
                <form action="index.php">
                    <input type="hidden" name="admin" value="empresas">
                    <input type="hidden" name="ordenarpor" value="<?= $_GET['ordenarpor'] ?? 'id'?>">
                    <input type="hidden" name="orden" value="<?= $_GET['orden'] ?? 'asc'?>">
                    <input type="text" name="buscarempresa" id="buscarEmpresa" placeholder="Buscar empresa..." class="search-input" value="<?= $_GET['buscarempresa'] ?? '' ?>">
                    <button type="submit" id="btnBuscarEmpresa" class="btn btn2">Buscar</button>
                </form>
                </div> 
                <div class="btn-crud-right"> 
                    <form action="/index.php?admin=invitarempresa" method="POST">
                        <button type="submit" id="add" class="btn2">+ Añadir</button>
                    </form>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><a href="?admin=empresas&buscarempresa=<?= ($_GET['buscarempresa'] ?? '') ?>&ordenarpor=id&orden=<?= (($_GET['orden'] ?? 'asc') == 'asc') ? 'desc' : 'asc' ?>">ID <img class="icon" src="img/orden.png" alt=""></a></th>
                            <th><a href="?admin=empresas&buscarempresa=<?= ($_GET['buscarempresa'] ?? '') ?>&ordenarpor=nombre&orden=<?= (($_GET['orden'] ?? 'asc') == 'asc') ? 'desc' : 'asc' ?>">Nombre <img class="icon" src="img/orden.png" alt=""></a></th>
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
                                            <button type="submit" class="btn2">Editar</button>
                                        </form>
                                        <form action="/index.php?admin=borrarempresa" method="POST">
                                            <input type="hidden" name="id" value="<?= $empresa->id ?>">
                                            <button type="submit" class="btn1">Borrar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <form class="pdf" action="/index.php?admin=generarpdf" method="POST">
                    <button type="submit" id="generarPDF" class="btn2">Generar PDF</button>
                </form>
            </div>
        </section>

    <?php $this->stop('contenidoPagina') ?>