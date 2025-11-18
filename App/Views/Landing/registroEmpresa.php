<?php $this->layout('layout', ['title' => 'Registro de Empresa | ProyectaFP']) ?>

<?php $this->start('contenidoPagina') ?>
    
    <section class="auth-container">
        <div class="auth-card">
            <form action="/index.php?page=registroempresapost" method="POST" enctype="multipart/form-data">
                <h1>Registro de Empresa</h1>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                
                <div class="form-group">
                    <label for="reppassword">Repetir Contraseña:</label>
                    <input type="password" name="reppassword" id="reppassword" required>
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

                <div>
                    <input type="checkbox" name="acepto" id="acepto" required>
                    <label for="acepto">Acepto los <a href="index.php?page=politicaprivacidad">Términos y Condiciones</a></label>
                </div>
                <input type="submit" value="Registrar" class="btn-primary">
                
            </form>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>