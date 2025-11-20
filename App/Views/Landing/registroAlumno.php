<?php $this->layout('layout', ['title' => 'Registro de Alumno | ProyectaFP']) ?>

<?php $this->start('js') ?>
    <script src="js/registroAlumno.js"></script>
    <script src="js/validator.js"></script>
<?php $this->stop() ?>

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
        <form action="/index.php?page=registroalumnopost" method="POST" enctype="multipart/form-data">
            <h1>Registro de Alumno</h1>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" name="foto" id="foto">
                <div>
                    <button class="btn1" id="abrirCamara">Activar Cámara</button>
                    <video id="video" class="video" playsinline autoplay></video>
                </div>
                <div class="controller">
                    <button class="btn1" id="snap">Capturar</button>
                </div>
                <canvas id="canvas" class="canvas"></canvas>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required><span class="error" id="error_email"></span>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" required><span class="error" id="error_password"></span>
            </div>

            <div class="form-group">
                <label for="reppassword">Repetir Contraseña:</label>
                <input type="password" name="reppassword" id="reppassword" required><span class="error" id="error_reppassword"></span>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required><span class="error" id="error_nombre"></span>
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos" required><span class="error" id="error_apellidos"></span>
            </div>

            <div class="form-group">
                <label for="familia">Familia Profesional:</label>
                <select name="familia_id" id="addFamilia" required>
                </select>
                <label for="ciclo">Ciclo Formativo:</label>
                <select name="ciclo_id" id="addCiclo" required>
                </select>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" id="telefono" required><span class="error" id="error_telefono"></span>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" required><span class="error" id="error_direccion"></span>
            </div>

            <div class="form-group">
                <label for="cv">CV (Solo PDF):</label>
                <input type="file" name="cv" id="cv" required accept=".pdf">
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