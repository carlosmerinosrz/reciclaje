<?php

    if (!empty($mensajeError)) {

        echo '<div class="error-message">¡' . $mensajeError . '!</div>';

    }

    if (!empty($mensajeBueno)) {

        echo '<div class="bueno-message">¡' . $mensajeBueno . '!</div>';

    }

?>

<form action="index.php?controlador=cbasura&metodo=crearBasura" method="post">

    <!-- Campos del contenedor -->

    <label for="nombre">Nombre de la basura:</label>

    <input type="text" id="nombre" name="nombre" >

    

    <label for="descripcion">Descripción de la basura:</label>

    <textarea id="descripcion" name="descripcion"></textarea>

    

    <label for="id_contenedor">Contenedor de la Basura:</label>

    <select id="id_contenedor" name="id_contenedor">
        <option value='NULL'>Punto Limpio</option>
        <?php foreach ($datos as $contenedor): ?>
            <?php if ($_GET['id'] == $contenedor['id_contenedor']): ?>
                <option value="<?php echo $contenedor['id_contenedor']; ?>" selected>
                    <?php echo $contenedor['nombre']; ?>
                </option>
            <?php endif; ?>

            <?php if ($_GET['id'] != $contenedor['id_contenedor']): ?>
                <option value="<?php echo $contenedor['id_contenedor']; ?>"><?php echo $contenedor['nombre']; ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>

    <!-- Botón de envío -->
    <input type="submit" value="Guardar">
</form>
<a href="index.php?controlador=cbasura&metodo=listadoBasuraContenedor&id_contenedor=<?php echo $contenedor['id_contenedor']; ?>" class="volverAtras">Volver atrás</a>            

