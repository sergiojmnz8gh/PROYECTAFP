<?php $this->layout('layout', ['title' => 'Política de Cookies | ProyectaFP']) ?>

<?php $this->start('contenidoPagina') ?>
    
    <section>
        <div class="politicas">
            <h1 class="page-title">Política de Cookies</h1>
                <h2>1. Uso de Cookies</h2>
            <p>Utilizamos cookies y tecnologías de seguimiento únicamente con fines técnicos, esenciales para el funcionamiento del servicio de empleo.</p>

            <h2>2. Cookie Estrictamente Necesaria y Exenta de Consentimiento</h2>
            <p>Al ser este un portal que requiere la identificación del usuario para su funcionamiento, utilizamos una única cookie de naturaleza técnica indispensable para el servicio de autenticación. Su uso está exento de requerir consentimiento según la normativa española (RGPD y LOPDGDD).</p>

            <h3>Detalle de la Cookie de Autenticación:</h3>
            <ul>
                <li><strong>Nombre:</strong> remember_token</li>
                <li><strong>Propósito:</strong> Identifica que el usuario ha iniciado sesión y mantiene su estado de autenticación mientras navega por el sitio y si marca "Recordarme", durante futuras sesiones mediante almacenamiento en localStorage.</li>
                <li><strong>Tipo:</strong> Técnica (esencial).</li>
                <li><strong>Duración:</strong> Sesión o Persistente (dependiendo de si se marca "Recordarme").</li>
            </ul>

            <h2>3. Gestión y Deshabilitación</h2>
            <p>Al utilizar este Sitio Web, aceptas el uso de esta cookie técnica, ya que es indispensable para el servicio solicitado.</p>
            <p>Puedes eliminar las cookies a través de la configuración de tu navegador, pero esto impedirá que puedas iniciar sesión y utilizar las funciones principales del portal.</p>
        </div>
    </section>

<?php $this->stop('contenidoPagina') ?>