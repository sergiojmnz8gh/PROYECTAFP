<?php $this->layout('layout', ['title' => 'Ficha de Empresa | ProyectaFP']) ?>

<?php $this->start('js') ?>
<?php $this->stop() ?>

<?php $this->start('contenidoPagina') ?>
    <section class="auth-container">
        <div class="auth-card">
                <h1>Ficha de Empresa</h1>
                
                <div class="div-ficha">
                    <img src="<?= $empresa->logo ?>">
                    <p><strong>Nombre:</strong> <?= $empresa->nombre ?></p>
                    <p><strong>Teléfono:</strong> <?= $empresa->telefono ?></p>
                    <p><strong>Dirección:</strong> <?= $empresa->direccion ?></p>
                    <p><strong>Email:</strong> <?= $empresa->email ?></p>

                </div>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>