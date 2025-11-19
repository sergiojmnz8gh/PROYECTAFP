<?php $this->layout('layout', ['title' => 'Editar Oferta | ProyectaFP']) ?>

<?php $this->start('js') ?>
<script src="js/listadoOfertas.js"></script>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section class="auth-container">
        <div class="auth-card">
            <form action="index.php?page=guardareditaroferta" method="POST" enctype="multipart/form-data">
                <h1>Editar Oferta</h1>
                
                <input type="hidden" name="id" value="<?= $oferta->id ?>">
                <div class="form-group">
                    <label for="titulo">Titulo:</label>
                    <input type="text" name="titulo" id="titulo" value="<?= $oferta->titulo ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="nombre">Descipción:</label>
                    <textarea name="desc" id="desc" rows="3" cols="50" required><?= $oferta->descripcion ?></textarea>
                </div>

                <div class="form-group">
                    <label for="familia">Familia:</label>
                    <select name="familia_id" id="familia">
                    </select>
                    <label for="ciclo">Ciclo:</label>
                    <select name="ciclo_id" id="ciclo">
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="fecha_inicio">Fecha Inicio:</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?= $oferta->fecha_inicio ?>" required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin">Fecha Fin:</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" value="<?= $oferta->fecha_fin ?>" required>
                </div>
                
                <input type="submit" value="Guardar" class="btn2">
                
            </form>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>