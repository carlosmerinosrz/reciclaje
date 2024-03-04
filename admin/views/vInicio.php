<div class="login wrap">
    <div class="h1">Login</div>
    <?php
        if (!empty($mensajeError)) {
            echo '<div class="error-message">' . $mensajeError . '</div>';
        }
    ?>
    <form action="index.php?controlador=cUsuarios&metodo=comprobarUsuarios" method="POST">
        <input placeholder="Usuario" id="usuario" name="usuario" type="text">
        <input placeholder="Password" id="pw" name="pw" type="password">
        <input value="enviar" class="btn" name="enviar" type="submit">
    </form>
</div>
