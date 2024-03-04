<?php
require_once __DIR__ . '/../models/conexion.php';

class MBasura extends Conexion{

    public function __construct(){
        parent::__construct();
    }

    public function agregarBasura($nombreBasura, $descripcionBasura,$id_contenedor) {
        try {
            if ($id_contenedor === 'NULL') {
                $sql = "INSERT INTO basura (nombre, descripcion, id_contenedor) VALUES (?, ?, null)";
            } else {
                $sql = "INSERT INTO basura (nombre, descripcion, id_contenedor) VALUES (?, ?, ?)";
            }
            $conexion = $this->conexion->prepare($sql);
            
            if ($id_contenedor === 'NULL') {
                $conexion->bind_param('ss', $nombreBasura, $descripcionBasura);
            } else {
                $conexion->bind_param('ssi', $nombreBasura, $descripcionBasura, $id_contenedor);
            }
            

            if ($conexion->execute()){
                $conexion->close();
                return true;
            } else {
                throw new Exception($conexion->error, $conexion->errno);
            }
        } catch (Exception $error) {
            $numeroError = $error->getCode();
            return $numeroError;
        }
    }

    public function borrarBasurasSeleccionadas($basurasSeleccionadas) {
        $basuras = implode(',', array_fill(0, count($basurasSeleccionadas), '?'));

        $sql = "DELETE FROM basura WHERE id_basura IN ($basuras)";
        $conexion = $this->conexion->prepare($sql);
        if($conexion->execute($basurasSeleccionadas))
            return true;
    }

    public function listadoBasura() {
        $sql = "SELECT * FROM basura";
        $conexion = $this->conexion->prepare($sql);
        $conexion->execute();
        $datos = [];
    
        $result = $conexion->get_result();
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }
        $conexion->close();
    
        return $datos;
    }

    public function listadoBasuraContenedor($id_contenedor) {
        if ($id_contenedor === 'NULL'){
            $sql = "SELECT b.id_basura, b.nombre AS nombre_basura, b.descripcion AS descripcion_basura, c.id_contenedor, c.nombre AS nombre_contenedor
            FROM basura b LEFT JOIN contenedores c ON b.id_contenedor = c.id_contenedor
            WHERE c.id_contenedor IS NULL;";
        }else{
            $sql = "SELECT b.id_basura, b.nombre AS nombre_basura, b.descripcion AS descripcion_basura, c.id_contenedor, c.nombre AS nombre_contenedor
            FROM basura b LEFT JOIN contenedores c ON b.id_contenedor = c.id_contenedor
            WHERE c.id_contenedor = $id_contenedor;";
        }
        
        $conexion = $this->conexion->prepare($sql);
        $conexion->execute();
        $datos = [];
    
        $result = $conexion->get_result();
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }
        $conexion->close();
    
        return $datos;
    }

    public function mBorrarBasura($id_basura) {
        $sqlDelete = "DELETE FROM basura WHERE id_basura = ?";
        $conexion = $this->conexion->prepare($sqlDelete);
        $conexion->bind_param("i", $id_basura);
        $resultado = $conexion->execute();
        $conexion->close();
    
        return $resultado;
    }
    
    public function msacarcontenedores(){
        $sql = "SELECT id_contenedor, nombre FROM contenedores";
        $conexion = $this->conexion->prepare($sql);
        $conexion->execute();
        $datos = [];
    
        $result = $conexion->get_result();
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }
        $conexion->close();
    
        return $datos;
    }

    public function msacarBasura($id_basura) {
        $contenedores = $this->msacarcontenedores();
    
        $sql = "SELECT b.id_basura, b.nombre AS nombre_basura, b.descripcion AS descripcion_basura, c.id_contenedor, c.nombre AS nombre_contenedor
            FROM basura b LEFT JOIN contenedores c ON b.id_contenedor = c.id_contenedor
            WHERE b.id_basura = ?;";
        $conexion = $this->conexion->prepare($sql);
        $conexion->bind_param("i", $id_basura);
        $conexion->execute();
        $datos = [];
    
        $result = $conexion->get_result();
        while ($fila = $result->fetch_assoc()) {
            // Agregamos la información de los contenedores al array de datos
            $fila['contenedores'] = $contenedores;
            $datos[] = $fila;
        }
        $conexion->close();
    
        return $datos;
    }
    
    public function mmodificarBasura($id_basura, $nombre, $descripcion, $id_contenedor){
        try{
            if ($id_contenedor === 'NULL'){
                $sql = "UPDATE basura SET nombre = ?, descripcion = ?, id_contenedor is null WHERE id_basura = ?";
            }else{
                $sql = "UPDATE basura SET nombre = ?, descripcion = ?, id_contenedor = ? WHERE id_basura = ?";
            }
            
            $conexion = $this->conexion->prepare($sql);
            $conexion->bind_param("ssii", $nombre, $descripcion, $id_contenedor, $id_basura);

            if ($conexion->execute()){
                $conexion->close();
                return true;
            } else {
                throw new Exception($conexion->error, $conexion->errno);
            }
        } catch (Exception $error) {
            $numeroError = $error->getCode();
            return $numeroError;
        }
    }
}
?>
