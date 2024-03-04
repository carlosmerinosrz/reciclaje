<?php

require_once __DIR__ . '/../models/conexion.php';

class MInicioSesion extends Conexion{

    public function __construct(){
        parent::__construct();
    }

    public function cerrarSesion() {
        session_start();
        session_unset();
        session_destroy();
    }

    public function verificarCredenciales($usuario, $contrasenia) {
        $stmt = $this->conexion->prepare("SELECT * FROM administradores WHERE usuario = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            $contrasenia_hash = $usuario['contrasenia'];

            // Verificar la contraseña
            if (password_verify($contrasenia, $contrasenia_hash)) {
                unset($usuario['contrasenia']); // No devolver la contraseña hash en el resultado
                return $usuario;
            }
        }

        return null;
    }

    public function crearAdmin($nombre, $usuario, $contrasenia, $perfil_admin) {
        try{
            $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT, ['cost' => 12]);
            $stmt = $this->conexion->prepare("INSERT INTO administradores (usuario, contrasenia, nombre, perfil_admin) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $usuario, $contrasenia_hash, $nombre, $perfil_admin);
            
            if ($stmt->execute()){
                $stmt->close();
                return true;
            } else {
                throw new Exception($conexion->error, $conexion->errno);
            }
        } catch (Exception $error) {
            $numeroError = $error->getCode();
            return $numeroError;
        }
    }

    public function existeAdmin() {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) as count FROM administradores WHERE perfil_admin = ?");
        $perfil_admin = 'admin';
        $stmt->bind_param("s", $perfil_admin);
        $stmt->execute();
        $result = $stmt->get_result();
        $fila = $result->fetch_assoc();
        $count = $fila['count'];

        return $count > 0; 
    }
}

?>
