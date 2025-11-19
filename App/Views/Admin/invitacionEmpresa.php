<?php $this->layout('layout', ['title' => 'Invitación de Empresa | ProyectaFP']) ?>

<?php $this->start('js') ?>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section class="auth-container">
        <div class="auth-card">
            <form action="index.php?admin=guardarinvitarempresa" method="POST" enctype="multipart/form-data">
                <h1>Invitación de Empresa</h1>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" id="direccion" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" required>
                </div>
                
                <div class="form-group">
                    <label for="logo">Logo (Imagen):</label>
                    <input type="file" name="logo" id="logo" accept="image/*">
                </div>
                
                <input type="submit" value="Registrar" class="btn2">
                
            </form>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>