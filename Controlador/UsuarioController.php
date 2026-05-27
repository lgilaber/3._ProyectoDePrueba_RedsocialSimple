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
            
            if (empty($nombre) || empty($email) || empty($password) || strlen($password) < 8) {
                $_SESSION['error'] = "Todos los campos son obligatorios y la contraseña debe tener al menos 8 caracteres.";
                header("Location: index.php?action=register");
                exit();
            }
            
            if ($password != $confirmar) {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
                header("Location: index.php?action=register");
                exit();
            }

            $nombre = $this->conn->real_escape_string(trim($nombre));
            $email = $this->conn->real_escape_string(trim($email));
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $imagen = NULL;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
                $imagen_nombre = time() . '_' . basename($_FILES['imagen']['name']);
                $imagen_temp = $_FILES['imagen']['tmp_name'];
                $imagen_destino = 'uploads/' . $imagen_nombre;
                
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                
                move_uploaded_file($imagen_temp, $imagen_destino);
                $imagen = $imagen_destino;
            }
            
            // Usar prepared statement para prevenir inyección SQL
            $stmt = $this->conn->prepare("INSERT INTO usuario (usr_name, usr_email, usr_pass, imagen) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $email, $password_hash, $imagen);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Usuario registrado exitosamente.";
                header("Location: index.php?action=login");
            } else {
                $_SESSION['error'] = "Error al registrar usuario: " . $stmt->error;
                header("Location: index.php?action=register");
            }
            $stmt->close();
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
            
            // Usar prepared statement para prevenir inyección SQL
            $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE usr_email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            if ($resultado && $resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
                
                if (password_verify($password, $usuario['usr_pass'])) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nombre'] = $usuario['usr_name'];
                    $_SESSION['usuario_email'] = $usuario['usr_email'];
                    $_SESSION['usuario_imagen'] = $usuario['imagen'];

                    $stmt->close();
                    header("Location: index.php?action=perfil");
                    exit();
                } else {
                    $_SESSION['error'] = "Contraseña incorrecta.";
                }
            } else {
                $_SESSION['error'] = "Usuario no encontrado.";
            }
            $stmt->close();
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
        
        $sql_pub = "SELECT * FROM publicacion WHERE usuario_id = ? ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($sql_pub);
        $stmt->bind_param("i", $_SESSION['usuario_id']);
        $stmt->execute();
        $resultado_pub = $stmt->get_result();
        $publicaciones = [];
        
        if ($resultado_pub) {
            while ($row = $resultado_pub->fetch_assoc()) {
                $publicaciones[] = $row;
            }
        }
        $stmt->close();

        require_once 'Vista/perfil.php';
    }
    
    public function verUsuario() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        $id_usuario = $_GET['id'] ?? 0;
        
        // Usar prepared statement para prevenir inyección SQL
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado && $resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $stmt->close();
            
            $sql_pub = "SELECT * FROM publicacion WHERE usuario_id = ? ORDER BY fecha DESC";
            $stmt_pub = $this->conn->prepare($sql_pub);
            $stmt_pub->bind_param("i", $id_usuario);
            $stmt_pub->execute();
            $resultado_pub = $stmt_pub->get_result();
            $publicaciones = [];
            
            if ($resultado_pub) {
                while ($row = $resultado_pub->fetch_assoc()) {
                    $publicaciones[] = $row;
                }
            }
            $stmt_pub->close();
            
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
            $stmt = $this->conn->prepare("INSERT INTO publicacion (usuario_id, mensaje, fecha) VALUES (?, ?, NOW())");
            $stmt->bind_param("is", $usuario_id, $mensaje);            
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Publicación creada exitosamente.";
            } else {
                $_SESSION['error'] = "Error al crear publicación: " . $stmt->error;
            }
            $stmt->close();
            
            header("Location: index.php?action=perfil");
            exit();
        }
    }
}
?>
