
<h1 class="nombre-pagina">Crear Nueva Cita</h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>

<?php
    include_once __DIR__ . "/../templates/barra.php";
?>

<div id="app">

    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios
        </button>

        <button type="button" data-paso="2">Información cita
        </button>

        <button type="button" data-paso="3">Resumen
        </button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios">
            
        </div>
    </div>

    <div id="paso-2" class="seccion">
        <h2>Tus datos y cita</h2>
        <p class="text-center">Coloca tus datos y fecha de tu cita</p>

        <form action="./index.php" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre" name="nombre" value="<?php echo $nombre ?>" disabled>
            </div>

            <div class="campo">
                <label for="fecha" class="wd-15">Fecha de la cita</label>
                <input type="date" id="fecha" name="fecha" min="<?php echo date("Y-m-d", strtotime('+20 hours')) ?>">
            </div>

            <div class="campo">
                <label for="hora" class="wd-15">Hora de la cita</label>
                <input type="time" id="hora" name="hora" step="1800">
            </div>
            <input type="hidden" id="id" value="<?php echo $id; ?>">

        </form>
    </div>

    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta</p>
    </div>

    <div class="paginacion">
        <button class="boton" id="anterior">
            &laquo; Anterior
        </button>
        <button class="boton" id="siguiente">
            Siguiente &raquo; 
        </button>
    </div>

    <?php
        $script = '
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="build/js/app.js"></script>
        
        ';
    ?>
</div>