<div class="barra">
    <p>Hola, <?php echo $nombre ?? ""?></p>

    <a class="boton" href="/logout">Cerrar Sesion</a>
</div>

<?php if (isset($_SESSION['admin'])) { ?>
    
    <div class="barra-servicios">
        <a href="/admin" class="boton-admin">Ver citas</a>
        <a href="/servicios" class="boton-admin">Ver servicios</a>
        <a href="/servicios/crear" class="boton-admin">Crear servicios</a>
    </div>

<?php } ?>