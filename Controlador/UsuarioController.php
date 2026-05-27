<?php
require_once 'Controlador/Database.php';

class UsuarioController {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConexion();
    }
    
    public function home() {
        if (isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=perfil");
            exit();
        }
        require_once 'Vista/home.php';
    }
    
    public function mostrarRegistro() {
        require_once 'Vista/registro.php';
    }
    
    public function registrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmar = $_POST['confirmar_password'] ?? '';
            
            if (empty($nombre) || empty($email) || empty($password) || strlen($password) < 6) {
                $_SESSION['error'] = "Todos los campos son obligatorios y la contraseña debe tener al menos 6 caracteres.";
                header("Location: index.php?action=register");
                exit();
            }
            
            if ($password != $confirmar) {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
                header("Location: index.php?action=register");
                exit();
            }
            
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $imagen = NULL;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $imagen_nombre = time() . '_' . $_FILES['imagen']['name'];
                $imagen_temp = $_FILES['imagen']['tmp_name'];
                $imagen_destino = 'uploads/' . $imagen_nombre;
                
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                move_uploaded_file($imagen_temp, $imagen_destino);
                $imagen = $imagen_destino;
            }
            
            $sql = "INSERT INTO usuario (usr_name, usr_email, usr_pass, imagen) VALUES ('$nombre', '$email', '$password_hash', '$imagen')";
            
            if ($this->conn->query($sql)) {
                $_SESSION['success'] = "Usuario registrado exitosamente.";
                header("Location: index.php?action=login");
            } else {
                $_SESSION['error'] = "Error al registrar usuario: " . $this->conn->error;
                header("Location: index.php?action=register");
            }
            exit();
        }
    }
    
    public function mostrarLogin() {
        require_once 'Vista/login.php';
    }
    
    public function iniciarSesion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: index.php?action=login");
                exit();
            }
            
            $sql = "SELECT * FROM usuario WHERE usr_email = '$email'";
            $resultado = $this->conn->query($sql);
            
            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
                
                if (password_verify($password, $usuario['usr_pass'])) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nombre'] = $usuario['usr_name'];
                    $_SESSION['usuario_email'] = $usuario['usr_email'];
                    $_SESSION['usuario_imagen'] = $usuario['imagen'];
                    
                    header("Location: index.php?action=perfil");
                    exit();
                } else {
                    $_SESSION['error'] = "Contraseña incorrecta.";
                }
            } else {
                $_SESSION['error'] = "Usuario no encontrado.";
            }
            
            header("Location: index.php?action=login");
            exit();
        }
    }
    
    public function mostrarPerfil() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        $sql = "SELECT id, usr_name, usr_email, imagen FROM usuario";
        $resultado = $this->conn->query($sql);
        $usuarios = [];
        
        while ($row = $resultado->fetch_assoc()) {
            $usuarios[] = $row;
        }
        
        $sql_pub = "SELECT * FROM publicacion WHERE usuario_id = " . $_SESSION['usuario_id'] . " ORDER BY fecha DESC";
        $resultado_pub = $this->conn->query($sql_pub);
        $publicacion = [];
        
        if ($resultado_pub) {
            while ($row = $resultado_pub->fetch_assoc()) {
                $publicacion[] = $row;
            }
        }
        
        require_once 'Vista/perfil.php';
    }
    
    public function verUsuario() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        $id_usuario = $_GET['id'] ?? 0;
        
        $sql = "SELECT * FROM usuario WHERE id = $id_usuario";
        $resultado = $this->conn->query($sql);
        
        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            
            $sql_pub = "SELECT * FROM publicacion WHERE usuario_id = $id_usuario ORDER BY fecha DESC";
            $resultado_pub = $this->conn->query($sql_pub);
            $publicaciones = [];
            
            if ($resultado_pub) {
                while ($row = $resultado_pub->fetch_assoc()) {
                    $publicaciones[] = $row;
                }
            }
            
            require_once 'Vista/ver_usuario.php';
        } else {
            $_SESSION['error'] = "Usuario no encontrado.";
            header("Location: index.php?action=perfil");
            exit();
        }
    }
    
    public function cerrarSesion() {
        session_destroy();
        header("Location: index.php");
        exit();
    }
    
    public function crearPublicacion() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mensaje = $_POST['mensaje'] ?? '';
            
            if (empty($mensaje)) {
                $_SESSION['error'] = "El mensaje no puede estar vacío.";
                header("Location: index.php?action=perfil");
                exit();
            }
            
            $usuario_id = $_SESSION['usuario_id'];
            $sql = "INSERT INTO publicacion (usuario_id, mensaje, fecha) VALUES ($usuario_id, '$mensaje', NOW())";
            
            if ($this->conn->query($sql)) {
                $_SESSION['success'] = "Publicación creada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al crear publicación.";
            }
            
            header("Location: index.php?action=perfil");
            exit();
        }
    }
}
?>
