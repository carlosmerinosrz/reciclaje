<div>
    <div class="login wrap">
        <div class="h1">REGISTRO DE ADMINISTRADOR</div>
        <form action="index.php?controlador=cUsuarios&metodo=crearBas" method="POST">
            <input placeholder="Nombre" id="nombre" name="nombre" type="text">
            <input placeholder="Usuario" id="usuario" name="usuario" type="text">
            <input placeholder="Password" id="pw" name="pw" type="password">
            <input value="enviar" class="btn" name="enviar" type="submit">
        </form>
        <?php
            if (!empty($mensajeError)) {
                echo '<div class="error-message">¡' . $mensajeError . '!</div>';
            }
        ?>
    </div>
</div>