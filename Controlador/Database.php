<?php
class Database {
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $database = "base_usuarios";
    private $conexion;
    
    public function __construct() {
        $this->conectar();
    }
    
    private function conectar() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->password, $this->database);
        
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        
        $this->conexion->set_charset("utf8");
    }
    
    public function getConexion() {
        return $this->conexion;
    }
    
    public function cerrarConexion() {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
?>
