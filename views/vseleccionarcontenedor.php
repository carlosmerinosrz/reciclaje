<div id="contenedorNegro">
    <form action="index.php?controlador=creciclaje&metodo=listadoBasuraContenedorFormulario" method="POST">
        <label for="contenedor">Selecciona un Contenedor:</label>
        <select id="id_contenedor" name="id_contenedor">
            <option value='NULL'>Punto Limpio</option>
            <?php foreach ($datos as $contenedor): ?>
            <option value="<?php echo $contenedor['id_contenedor']; ?>"><?php echo $contenedor['nombre']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Comprobar Basuras">
    </form>
</div>

