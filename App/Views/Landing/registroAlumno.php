<?php $this->layout('layout', ['title' => 'Registro de Alumno | ProyectaFP']) ?>

<?php $this->start('js') ?>
    <script src="js/registroAlumno.js"></script>
    <script src="js/validator.js"></script>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>

<section class="auth-container">
    <div class="auth-card">
        <form action="/index.php?page=registroalumnopost" method="POST" enctype="multipart/form-data">
            <h1>Registro de Alumno</h1>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" name="foto" id="foto">
                <div>
                    <button id="abrirCamara">Activar Cámara</button>
                    <video id="video" class="video" playsinline autoplay></video>
                </div>
                <div class="controller">
                    <button id="snap">Capturar</button>
                </div>
                <canvas id="canvas" class="canvas"></canvas>
            </div>

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
                <label for="apellidos">Apellidos:</label>
                <input type="text" name="apellidos" id="apellidos" required>
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
                <input type="text" name="telefono" id="telefono">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" id="direccion" required>
            </div>

            <div class="form-group">
                <label for="cv">CV (Solo PDF):</label>
                <input type="file" name="cv" id="cv" required accept=".pdf">
            </div>

            <input type="checkbox" name="acepto" id="acepto">
            <label for="acepto">Acepto los <a href="index.php?page=politicaprivacidad">Términos y Condiciones</a></label>
            <input type="submit" value="Registrar" class="btn-primary">

        </form>
    </div>
</section>

<?php $this->stop('contenidoPagina') ?>