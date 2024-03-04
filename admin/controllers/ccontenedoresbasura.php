<?php

require_once '../librerias/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;

class CcontenedoresBasura {

    public $vista;
    public $mensaje;
    public $mensajebueno;
    public $id_contenedor;
    private $objContenedoresBasura;

    public function __construct() {
        require_once __DIR__ . '/../models/mcontenedoresbasura.php';
        require_once __DIR__ . '/../models/mbasuras.php';
        require_once __DIR__ . '/../controllers/cUsuarios.php';

        $this->vista = 'vErrorContenedores';
        $this->objContenedoresBasura = new MContenedoresBasura();
        $this->objBasura = new MBasura();
        $this->objInicio = new Cusuarios();
    }

    public function mostrarMenuInicial() {
        $this->objInicio->comprobarSession();
        $this->vista = 'vmenuinicial';
    }

    public function listadoContenedores() {
        $this->objInicio->comprobarSession();
        $this->vista = 'vlistarcontenedores';
        $datos = $this->objContenedoresBasura->listarContenedores();
        return $datos;
    }

    public function mostrarFormContenedores() {
        $this->objInicio->comprobarSession();
        $this->vista = 'valtacontenedores';
    }

    public function generarPdf() {
        $datos = $this->objContenedoresBasura->listarContenedores();
        $this->generarVistaPdf($datos);
    }

    public function generarVistaPdf($datos) {
        $this->objInicio->comprobarSession();
        ob_start();
        include 'views/generarPdf.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $options = $dompdf->getOptions();

        $options->set(array('isRemoteEnabled' => true));
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html); // le pasamos el contenido del html que queremos pasar a pdf
        $dompdf->setPaper('A4', 'protrait'); // Tamaño del papel y orientacion
        $dompdf->render();
        $dompdf->stream("listado_contenedores_carlos_merino.pdf"); // nombre del pdf
    }


    public function procesarFormulario() {
        $this->objInicio->comprobarSession();
        $this->vista = 'valtacontenedores';
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST["nombre"]) && isset($_POST["descripcionContenedor"])) {
                if (isset($_FILES["image"]["tmp_name"]) && !empty($_FILES["image"]["tmp_name"])) {
                    $fileType = $_FILES["image"]["type"];
    
                    // Verificar el tipo de archivo mediante la información del tipo (mime type)
                    if ($fileType !== 'image/jpeg') {
                        $this->mensaje = "Error al procesar el formulario: Debes seleccionar una imagen con formato JPG o JPEG.";
                        return;
                    }
    
                    $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
                } else {
                    $this->mensaje = "Error al procesar el formulario: Debes seleccionar una imagen.";
                    return;
                }
    
                $nombre = $_POST["nombre"];
                $nombre = ($nombre === '') ? NULL : $nombre;
    
                $descripcionContenedor = $_POST["descripcionContenedor"];
                $descripcionContenedor = ($descripcionContenedor === '') ? NULL : $descripcionContenedor;
    
                $nombreBasuraArray = [];
                $descripcionBasuraArray = [];
    
                // Aquí voy a almacenar los nombres y la descripción
                for ($i = 1; $i <= 5; $i++) {
                    $nombreBasura = isset($_POST["nombreBasura$i"]) ? $_POST["nombreBasura$i"] : "";
                    $descripcionBasura = isset($_POST["descripcionBasura$i"]) ? $_POST["descripcionBasura$i"] : "";
    
                    // Solo se agregarán a los contenedores aquellas basuras que tienen nombre
                    if (!empty($nombreBasura)) {
                        $nombreBasuraArray[] = $nombreBasura;
                        $descripcionBasuraArray[] = $descripcionBasura;
                    }
                }
    
                // Verificar si al menos una basura tiene un nombre
                if (empty($nombreBasuraArray)) {
                    $this->mensaje = "Error al procesar el formulario: Debes rellenar al menos una basura con nombre.";
                    return;
                }
    
                try {
                    $id_contenedor = $this->objContenedoresBasura->crearContenedorBasura($nombre, $imageData, $descripcionContenedor);
    
                    // Si existe el contenedor le añadimos las basuras
                    if ($id_contenedor) {
                        for ($i = 0; $i < count($nombreBasuraArray); $i++) {
                            $this->objContenedoresBasura->agregarBasura($id_contenedor, $nombreBasuraArray[$i], $descripcionBasuraArray[$i]);
                        }
    
                        $this->mensajebueno = "Se ha creado correctamente el contenedor con sus basuras";
                        return;
                    }
                } catch (Exception $mensaje) {
                    $codigoError = $mensaje->getCode();
                    $this->obtenerMensajeError($codigoError);
                }
            }
        }
    }

    
    // ------ BORRADO DE CONTENEDORES --------------------------------------------------------------
    public function borrarContenedores() {
        $this->vista = 'vborrado';
        $id = $_GET['id'];
    
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['confirmacion'])) {
                $respuesta = $_POST['confirmacion'];
                if ($respuesta === 'si') {
                    $resultado = $this->objContenedoresBasura->mBorrarContenedor($id);
                    $this->mensajebueno = "Se ha borrado correctamente el contenedor. Las basuras del contenedor puedes asignarlas a otros contenedores desde la Gestion de Basura.";
                    $this->vista = 'vErrorContenedores';
                    return;
                } else {
                    header("Location: index.php?controlador=ccontenedoresbasura&metodo=listadoContenedores");
                    exit();
                }
            }
        }
    }
    
    // ------ MODIFICAR LAS BASURAS QUE TIENE UN CONTENEDOR --------------------------------------------------------------
    public function mModifBasurasContenedor() {
        $this->objInicio->comprobarSession();
        $this->vista = 'informacioncontenedores';
        $id = $_GET['id'];
        $datosContenedor = $this->objContenedoresBasura->mObtenerContenedorBasura($id);
        return $datosContenedor;
    }
    
    // ------ OBTENER LOS DATOS DE LOS CONTENEDORES CON SUS BASURA --------------------------------------------------------------
    public function mObtenerContenedorBasura() {
        $this->objInicio->comprobarSession();
        $this->vista = 'vmodificarcontenedor';
        $id = $_GET['id'];
        $datosContenedor = $this->objContenedoresBasura->mObtenerContenedorBasura($id);
        return $datosContenedor;
    }
        
// ------ MODIFICACION DE CONTENEDORES Y LAS BASURAS DEL CONTENEDOR --------------------------------------------------------------
    public function cmodificarcontenedor() {
        $this->objInicio->comprobarSession();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $nombre = $_POST["nombre"];
            $descripcion = $_POST["descripcion"];
            $idContenedor = $_GET['id'];
            $nombre = ($nombre === '') ? NULL : $nombre;
            $descripcion = ($descripcion === '') ? NULL : $descripcion;

            // Verifica si se ha subido una img y si tiene contenido
            if (isset($_FILES["image"]["tmp_name"]) && !empty($_FILES["image"]["tmp_name"])) {

                $fileType = $_FILES["image"]["type"];

                // Verificar el tipo de archivo mediante la información del tipo (mime type)
                if ($fileType !== 'image/jpeg') {
                    $this->mensaje = "Error al procesar el formulario: Debes seleccionar una imagen con formato JPG o JPEG.";
                    return;
                }
                $imageData = file_get_contents($_FILES["image"]["tmp_name"]);
            } else {
                $this->mensaje = "Error al procesar el formulario: Debes seleccionar una imagen.";
                return;
            }
            $resultado1 = $this->objContenedoresBasura->mmodifcontenedor($idContenedor, $nombre, $descripcion, $imageData);

            foreach ($_POST as $devuelve => $nombreBasura) {
                if (strpos($devuelve, 'nombre_basura_') === 0) {

                    $idBasura = substr($devuelve, strlen('nombre_basura_'));
                    $descripcionDevuelve = 'descripcion_basura_' . $idBasura;
                    $descripcionDevuelve = isset($_POST[$descripcionDevuelve]) ? $_POST[$descripcionDevuelve] : NULL;
                    $nombreBasura = ($nombreBasura === '') ? NULL : $nombreBasura;
                    $resultado2 = $this->objBasura->mmodificarBasura($idBasura, $nombreBasura, $descripcionDevuelve, $idContenedor);
                }
            }

            if ($resultado1 === true && $resultado2 === true) {
                $this->mensajebueno = 'Contenedor y basuras modificados exitosamente';
                return;
            } else {
                $this->vista = 'vErrorContenedores';
                $this->obtenerMensajeError($resultado1);
            }
        }
    }

    // ------ MENSAJES DE ERROR --------------------------------------------------------------

    public function obtenerMensajeError($codigoError) {
        $this->mensaje = "Error. Código de error: " . $codigoError;
        switch ($codigoError) {
            case 1048:
                $this->mensaje = "Error al procesar el formulario: No puede haber campos vacíos.";
                break;
            case 1406:
                $this->mensaje = "Error al procesar el formulario: Los campos exceden la longitud máxima.";
                break;
            case 1062:
                $this->mensaje = "Error al procesar el formulario: Ya existe un contenedor con ese nombre.";
                break;
            default:
                if (is_numeric($codigoError)) {
                    $this->mensaje = "Error al crear contenedor. Código de error: $codigoError";
                } else {
                    $this->mensaje = $codigoError;
                }
                break;
        }
        return $this->mensaje;
    }

    // ------ BORRAR LAS BASURAS CUANDO MODIFICAMOS SOLO LAS BASURA DE LOS CONTENEDORES -------------------------------------

    public function borrarBasurasContenedores($id) {
        return $this->objContenedoresBasura->borrarBasurasContenedores($id);
    }

    public function crearBasurasNuevas() {

        $this->objInicio->comprobarSession();
        $idContenedor = $_GET['id'];
        $nuevasBasuras = array();
        $camposVacios = false;

        //$_POST recorre todas las peticiones POST que se han hecho en el formulario
        foreach ($_POST as $devuelve => $nombre) {
            // strpos => comprueba si la cadena comienza por caracteres
            // Verificamos si el nombre del campo comienza con "nombre_basura_" para identificar los campos de nombre de basura
            if (strpos($devuelve, 'nombre_basura_') === 0/**si esta cadena esta en la posicion 0 */) {

                // Verificar si el nombre está en blanco
                if (empty($nombre)) {
                    $camposVacios = true;
                    break;
                }
                // Cogemos el id que esta despues de la cadena en este caso el id_basura
                $idBasura = substr($devuelve, strlen('nombre_basura_'));

                // Construimos el nombre del campo de descripción correspondiente
                $descripcionDevuelve = 'descripcion_basura_' . $idBasura;

                // Verificamos si existe una descripción para esta basura
                $descripcion = isset($_POST[$descripcionDevuelve]) ? $_POST[$descripcionDevuelve] : NULL;

                //Agregamos la nueva basura al array
                $nuevasBasuras[] = array(
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                    'id_contenedor' => $idContenedor
                );
            }
        }

        // Si hay campos de nombre de basura en blanco, no hacemos ninguna operación
        if ($camposVacios) {
            $this->vista = 'vErrorContenedores';
            $this->mensaje = "Error no puede haber ningun Nombre en blanco, si deseas eliminar una basura pulsa la cruz";
            return;
        }

        // Borramos las basuras existentes en el contenedor solo si no hay campos de nombre de basura en blanco
        $resultado = $this->borrarBasurasContenedores($idContenedor);

        if ($resultado === true) {
            foreach ($nuevasBasuras as $basura) {
                $nombre = $basura['nombre'];
                $descripcion = $basura['descripcion'];
                $idContenedor = $basura['id_contenedor'];
                $nombre = ($nombre === '') ? NULL : $nombre;
                $descripcion = ($descripcion === '') ? NULL : $descripcion;

                $this->objContenedoresBasura->crearBasurasNuevas($nombre, $descripcion, $idContenedor);
            }
            $this->mensajebueno = "Se ha modificado correctamente las basuras de su contenedor";
            return;
        }
    }

    public function insertarCSV() {
        $tipo = $_FILES['dataCliente']['type'];
        $tamanio = $_FILES['dataCliente']['size'];
        $archivotmp = $_FILES['dataCliente']['tmp_name'];
        $lineas = file($archivotmp);
        $cantidad_registros_agregados = 0;

        $i = 0;

        foreach ($lineas as $linea) {

            $cantidad_registros = count($lineas);
            $cantidad_regist_agregados = ($cantidad_registros - 1);

            if ($i != 0) {
                $datos = explode(";", $linea);
                $nombreContenedor = $datos[0];
                $imgContenedor = !empty($datos[1]) ? $datos[1] : '';
                $imgContenedor = !empty($datos[2]) ? $datos[2] : NULL;

                $resultado = $this->objContenedoresBasura->crearContenedorBasura($nombreContenedor, $imgContenedor, $imgContenedor);
                if ($resultado) {
                    $cantidad_registros_agregados++;
                }
            }
            $i++;
        }
        return $this->mensajebueno = "Se han insertado " . $cantidad_registros_agregados . " de " . $cantidad_registros . "registros";
    }
}
