<?php

class Cusuarios {

    // Propiedades de la clase
    public $nombrePagina;
    public $vista;
    public $objInicio;
    public $mensaje;
    public $mensajebueno;

    // Constructor de la clase
    function __construct() {
        require_once __DIR__ . '/../models/miniciosesion.php';
        $this->objInicio = new MInicioSesion();
    }

    // Método para mostrar el formulario inicial
    public function formularioInicial() {
        $this->vista = 'vInicio';
        $this->nombrePagina ='';
    }

    // Método para comprobar la sesión del usuario
    public function comprobarSession(){
        session_start();
        if (!isset($_SESSION['id_admin'])) {
            header("Location: index.php?controlador=cUsuarios&metodo=formularioInicial");
            exit();
        }
    }

    // Método para comprobar las credenciales de inicio de sesión
    public function comprobarUsuarios() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
            $usuario = $_POST['usuario'];
            $pw = $_POST['pw'];

            $usuario1 = $this->objInicio->verificarCredenciales($usuario, $pw);

            if (is_array($usuario1)) {
                session_start();
                $_SESSION['id_admin'] = $usuario1['id_admin'];
                $_SESSION['perfil_admin'] = $usuario1['perfil_admin'];

                switch ($_SESSION['perfil_admin']) {
                    case 'admin':
                        header("Location: index.php?controlador=ccontenedoresbasura&metodo=mostrarMenuInicial");
                        exit();
                        break;
                }
            } else {
                $this->vista = 'vInicio';
                $this->mensaje = "Credenciales incorrectas. Por favor, inténtelo de nuevo.";
                return;
            }
        }
    }

    // Método para crear un administrador
    public function crearBas(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar'])) {
            $nombre = $_POST['nombre'];
            $usuario = $_POST['usuario'];
            $pw = $_POST['pw'];
            $perfil = 'admin';

            $nombre = ($nombre === '') ? NULL : $nombre;
            $usuario = ($usuario === '') ? NULL : $usuario;
            $pw = ($pw === '') ? NULL : $pw;
            $perfil = ($nombre === '') ? NULL : $perfil;

            $resultado = $this->objInicio->crearAdmin($nombre, $usuario, $pw, $perfil);
            if($resultado === true){
                header("Location: index.php?controlador=cUsuarios&metodo=formularioInicial");
                exit();
            }else{
                $this->vista = 'Instalacion';
                $this->obtenerMensajeError($resultado);
            }
        }
    }

    // Método para cerrar la sesión del usuario
    public function cerrarSesion() {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function obtenerMensajeError($codigoError) {
        $this->mensaje = "Error. Código de error: " . $codigoError;
        switch ($codigoError) {
            case 1048:
                $this->mensaje = "Error al procesar el formulario: No puede haber campos vacíos.";
                break;
            case 1406:
                $this->mensaje = "Error al procesar el formulario: Los campos exceden la longitud máxima.";
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
}
?>
