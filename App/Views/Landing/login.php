<?php $this->layout('layout', ['title' => 'Iniciar Sesión | ProyectaFP']) ?>

<?php $this->start('contenidoPagina') ?>
    <section class="auth-container">
        <?php 
            use App\Helpers\Sesion;
            $error = Sesion::leerSesion('login_error');
            if ($error) {
                echo '<div class="error">'. $error .'</div>';
                Sesion::escribirSesion('login_error', null);
            }
            
            $message = Sesion::leerSesion('login_message');
            if ($message) {
                echo '<div>'. $message .'</div>';
                Sesion::escribirSesion('login_message', null);
            }
        ?>

        <div class="auth-card">
            <form action="/index.php?page=loginpost" method="POST"> 
                <h1>Iniciar Sesión</h1>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="form-row-actions">
                    <div class="recuerdame">
                        <input type="checkbox" name="recuerdame" id="recuerdame" class="checkbox-input">
                        <label for="recuerdame" class="checkbox-label">Recuérdame</label>
                    </div>
                    <input type="submit" name="submit" value="Iniciar Sesión" class="btn1">
                </div>
            </form>
        </div>
    </section>
<?php $this->stop('contenidoPagina') ?>