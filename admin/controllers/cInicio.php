<?php

class CInicio {
    public $nombrePagina;
    public $vista;
    private $objInicio;
    public $mensaje;
    public $id_contenedor;
    public $mensajebueno;

    public function __construct() {
        require_once __DIR__ . '/../models/miniciosesion.php';
        $this->objInicio = new MInicioSesion();
    }

    public function mostrarInicioSesion() {
        if ($this->objInicio->existeAdmin()) {
            $this->nombrePagina = 'Inicio Sesion';
            $this->vista = 'vInicio';
        } else {
            $this->nombrePagina = 'Registrate como Administrador';
            $this->vista = 'Instalacion';
        }
    }
}
?>
