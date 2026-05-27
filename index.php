<?php
session_start();

function autoload($class) {
    $file = 'Controlador/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('autoload');

$action = $_GET['action'] ?? 'home';

$controller = new UsuarioController();

switch ($action) {
    case 'register':
        $controller->mostrarRegistro();
        break;
    case 'guardar_usuario':
        $controller->registrarUsuario();
        break;
    case 'login':
        $controller->mostrarLogin();
        break;
    case 'iniciar_sesion':
        $controller->iniciarSesion();
        break;
    case 'perfil':
        $controller->mostrarPerfil();
        break;
    case 'ver_usuario':
        $controller->verUsuario();
        break;
    case 'logout':
        $controller->cerrarSesion();
        break;
    case 'publicar':
        $controller->crearPublicacion();
        break;
    default:
        $controller->home();
        break;
}
?>
