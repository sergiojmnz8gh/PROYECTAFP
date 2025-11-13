<?php $this->layout('layout', ['title' => 'Listado de Empresas | ProyectaFP']) ?>

<?php $this->start('js') ?>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section>
        <h1 class="page-title">Listado de Empresas</h1>
        
        <div class="search-controls">
            <div class="search_group">
            <form action="listadoempresas.php" method="GET">
                <input type="text" name="buscarEmpresa" id="buscarEmpresa" placeholder="Buscar empresa..." class="search-input" value="<?= $_GET['buscarEmpresa'] ?? '' ?>">
                <button type="submit" id="btnBuscarEmpresa" class="btn btn2">Buscar</button>
            </form>
            </div> 
            <div class="btn-crud-right"> 
                <button type="button" id="add" class="btn-action btn2"><a href="/index.php?admin=invitacionempresa">+ Añadir</a></button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empresas as $empresa): ?>
                        <tr>
                            <td><?= $empresa->id ?></td>
                            <td><?= $empresa->nombre ?></td>
                            <td><?= $empresa->email ?></td>
                            <td class="div-actions">
                                <img class="btn-ficha" src=<?= $empresa->logo ?>>
                                <div class="div-acciones">
                                    <button type="button" class="btn-action btn2">Editar</button>
                                    <button type="button" class="btn-action btn1">Borrar</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>