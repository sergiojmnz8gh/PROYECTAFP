<?php $this->layout('layout', ['title' => 'Editar Empresa | ProyectaFP']) ?>

<?php $this->start('js') ?>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section class="auth-container">
        <div class="auth-card">
            <form action="index.php?admin=guardareditarempresa" method="POST" enctype="multipart/form-data">
                <h1>Editar Empresa</h1>
                
                <input type="hidden" name="id" value="<?= $empresa->id ?>">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?= $empresa->email ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="<?= $empresa->nombre ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" id="direccion" value="<?= $empresa->direccion ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" value="<?= $empresa->telefono ?>" required>
                </div>

                <input type="submit" value="Guardar" class="btn2">
            </form>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>