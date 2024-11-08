<h1 class="nombre-pagina">Panel de administracion</h1>

<?php
    include_once __DIR__ . "/../templates/barra.php";
?>

<h2>Buscar citas</h2>
<div class="busqueda">
    <form action="/views/admin/admin.php" class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha ;?>">
        </div>
    </form>
</div>

<?php
    if (count($citas) === 0) {
        echo "<p> No hay citas agendadas para esta fecha </p>";
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php 
        $idCita = 0; // Inicializamos la cita
        foreach ($citas as $key => $cita) {
            if ($idCita !== $cita->id) {
               $total = 0; 
        ?>
        
        <li>
            <p>ID: <span><?php echo $cita->id; ?></span></p>
            <p>Hora: <span><?php echo $cita->hora; ?></span></p>
            <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
            <p>Email: <span><?php echo $cita->email; ?></span></p>
            <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>

            <h3>Servicios</h3>

            <?php 
                $idCita = $cita->id;
            } // Final if
            $total += $cita->precio;
            ?>

            <p class="servicio"><?php echo $cita->servicio . " ---->  " . $cita->precio. " $"; ?></p>
        
        
        <?php
            $actual = $cita->id;
            $proximo = $citas[$key + 1]->id ?? 0; // En este caso la key seria el indice

            if(esUltimo($actual, $proximo)){?>

                <p class="total ">Total: <span><?php echo $total; ?> $</span></p>

                <form action="/api/eliminar" method="POST" class="formulario">
                    <div class="delete">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" value="Eliminar" class="boton-eliminar">
                    </div>
                </form>

            <?php   
            }

        } // Fin de foreach
        ?>
    </ul>
</div>

<?php
    $script = "<script src='build/js/buscador.js'></script>"
?>