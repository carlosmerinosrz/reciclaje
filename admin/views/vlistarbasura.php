<div id="listarContenedores">
    <?php if (!empty($datos)): ?>
        <h2><span>Listado de Basuras del contenedor</span> <?php echo ($datos[0]['nombre_contenedor']=== NULL)? 'Punto Limpio' : $datos[0]['nombre_contenedor']; ?></h2>
        <div id="columnasContenedores">
            <form action="index.php?controlador=cbasura&metodo=borrarBasurasSeleccionadas&id_contenedor=<?php echo $datos[1]['id_contenedor']; ?>" method="post">
                <a href="index.php?controlador=cbasura&metodo=generarPdf" target="_blank" class="enlacesPdf">Generar Pdf</a>
                <a href="index.php?controlador=cbasura&metodo=mostrarFormBasura&id=<?php echo $datos[0]['id_contenedor']; ?>" class="enlacesAlta">Alta de Basuras</a>
                <a  onclick="seleccionarTodasLasBasuras()" class="enlacesSeleccionar">seleccionar todas las basuras</a>
                <p><span>Elige las basuras que deseas eliminar y luego haz clic en el botón "Eliminar seleccionados" ubicado debajo.</span></p>
                <table id="tablaContenedores">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos as $basura): ?>
                            <tr>
                                <td><input type="checkbox" name="seleccionar[]" value="<?php echo $basura['id_basura']; ?>"></td>
                                <td><?php echo $basura['id_basura'] ?></td>
                                <td><?php echo $basura['nombre_basura'] ?></td>
                                <td><?php echo ($basura['descripcion_basura'] === NULL) ? 'Sin Descripcion' : $basura['descripcion_basura']; ?></td>
                                <td>
                                    <!-- <a class="aBorrar" href="index.php?controlador=cbasura&metodo=borrarbasura&id=<?php echo $basura['id_basura']; ?>&id_contenedor=<?php echo $basura['id_contenedor']; ?>">Borrar</a> -->
                                    <a class="aEditar" href="index.php?controlador=cbasura&metodo=mostrarFormModfBasura&id=<?php echo $basura['id_basura']; ?>&id_cont_sin=<?php echo $basura['id_contenedor']; ?>">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <input type="submit" value="Eliminar Basuras Seleccionadas" class="enlacesTodos">
            </form>
            <div id="paginacion"></div>
        </div>
    <?php else: ?>
        <p>No hay datos disponibles.</p>
        <a href="index.php?controlador=cbasura&metodo=mostrarFormBasura&id=<?php echo $datos[0]['id_contenedor']; ?>" class="enlacesAlta">Alta de Basuras</a>
    <?php endif; ?>
</div>
<a href="index.php?controlador=cbasura&metodo=eleccionGestion" class="volverAtras">Volver atrás</a>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    
$(document).ready(function () {
  $('#tablaContenedores').DataTable({
    language: {
      "decimal": "",
      "emptyTable": "No hay información",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
      "infoFiltered": "(Filtrado de _MAX_ total entradas)",
      "infoPostFix": "",
      "thousands": ",",
      "lengthMenu": "Mostrar _MENU_ Entradas",
      "loadingRecords": "Cargando...",
      "processing": "Procesando...",
      "search": "Buscar:",
      "zeroRecords": "Sin resultados encontrados",
      "paginate": {
        "first": "Primero",
        "last": "Ultimo",
        "next": "Siguiente",
        "previous": "Anterior"
      }
    },
    "paging": true,
    "ordering": true,
    "info": true,
    "searching": true,
    "pageLength": 5
  });
});

function seleccionarTodasLasBasuras() {
  // Obtén todos los elementos de tipo checkbox en el formulario
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  // Itera sobre cada checkbox y cambia el estado de la casilla
  checkboxes.forEach(function(checkbox) {
      checkbox.checked = !checkbox.checked;
  });
}
</script>
