<?php $this->layout('layout', ['title' => 'Registro de Empresa | ProyectaFP']) ?>

<?php $this->start('contenidoPagina') ?>
    
    <section class="auth-container">
        <?php 
            use App\Helpers\Sesion;
            $error = Sesion::leerSesion('registro_error');
            if ($error) {
                echo '<div class="error">'. $error .'</div>';
                Sesion::escribirSesion('registro_error', null);
            }
            
            $message = Sesion::leerSesion('registro_message');
            if ($message) {
                echo '<div>'. $message .'</div>';
                Sesion::escribirSesion('registro_message', null);
            }
        ?>
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
                    <input type="file" name="logo" id="logo" required accept="image/*">
                </div>

                <div class="aceptregistro">
                    <input class="checkbox-input" type="checkbox" name="acepto" id="acepto" required>
                    <label for="acepto">Acepto los <a href="index.php?page=politicaprivacidad">Términos y Condiciones</a></label>
                </div>
                <input type="submit" value="Registrar" class="btn2">
                
            </form>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>