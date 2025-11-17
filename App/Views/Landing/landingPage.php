<?php $this->layout('layout', ['title' => 'Inicio | ProyectaFP']) ?>

<?php $this->start('contenidoPagina') ?>
        <section>
        <article class="banner" id="alumno">
            <h1>Conectamos el talento FP con la empresa ideal</h1>
            <p class="descr">Tu próximo desafío profesional te está esperando. Conéctate con oportunidades únicas en tu sector y da el siguiente paso en tu carrera.<br><br>En ProyectaFP, hemos creado una comunidad dinámica de alumnos de FP y empresas líderes para ayudarte a descubrir oportunidades de empleo únicas, compartir experiencias y construir tu trayectoria profesional.</p>
        </article>
        <article>
            <h2>Forma parte de la comunidad</h2>
            <div class="cards">
                <div class="card">
                    <p class="titulillo">¿Eres un alumno finalizando o que acaba de finalizar estudios de FP?</p>
                    <p>Prepara tu CV y te ayudamos a buscar las ofertas que se adapten a tus habilidades y conocimientos.</p>
                    <a href="index.php?page=registroalumno"><button class="btn2" id="empresa">Soy Alumno</button></a>
                </div>
                <img class="imagen" src="img/alumno.jpg" alt="Alumno de FP">
            </div>
            <div class="cards">
                <img class="imagen" src="img/empresa.jpg" alt="Profesionales en oficina">
                <div class="card">
                    <p class="titulillo">¿Eres una empresa en busca de talento de FP?</p>
                    <p>Cuéntanos lo que buscas y te ayudamos a encontrar y seleccionar los empleados ideales para impulsar tu empresa.</p>
                    <a href="index.php?page=registroempresa"><button class="btn2">Soy Empresa</button></a>
                </div>
            </div>
        </article>
        <article>
            <h2>Empresas líderes</h2>
            <div class="cards">
                <div class="card">
                </div>
                <div class="card">
                </div>
                <div class="card">
                </div>
            </div>
        </article>
    </section>
<?php $this->stop('contenidoPagina') ?>