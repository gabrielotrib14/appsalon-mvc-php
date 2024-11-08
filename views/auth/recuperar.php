<h1 class="nombre-pagina">Reestablecer Password</h1>
<p class="descripcion-pagina">Coloca tu nueva Password a continuacion</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";

    if ($error) {
        return;
    }
?>



<form method="POST" class="formulario">
    <div class="campo">
        <label for="password" class="wd-15">Nueva Password</label>
        <input type="password" placeholder="Password" id="password" name="password">
    </div>

    <input type="submit" value="Reestablecer" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>

    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
</div>