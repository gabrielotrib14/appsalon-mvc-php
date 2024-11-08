<h1 class="nombre-pagina">Olvide mi Password</h1>

<p class="descripcion-pagina">Reestablece tu password, escribe el email con el que te registraste y te enviaremos un correo con las instrucciones</p>

<?php include_once __DIR__ . "/../templates/alertas.php"; ?>

<form action="/olvide" class="formulario" method="POST">

    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" placeholder="Tu E-mail"
        name="email" id="email">
    </div>
    
    <input type="submit" value="Enviar instrucciones" class="boton">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesion</a>

    <a href="/crear-cuenta">¿Aun no tienes una cuenta? Crear una</a>
</div>