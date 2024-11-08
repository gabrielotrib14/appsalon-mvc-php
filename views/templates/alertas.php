
<?php 
foreach ($alertas as $key => $mensajes) { 
    foreach ($mensajes as $mensaje) { ?>
        <div class="alertas <?php echo $key?>">
            <?php echo $mensaje; ?> 
        </div>
    <?php }
} ?>
