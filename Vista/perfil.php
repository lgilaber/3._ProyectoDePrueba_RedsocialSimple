<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Red Social Simple</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar h2 {
            font-size: 24px;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        .perfil-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .perfil-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .perfil-imagen {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }
        
        .perfil-info h1 {
            color: #333;
            margin-bottom: 5px;
        }
        
        .perfil-info p {
            color: #666;
        }
        
        .publicacion-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .publicacion-form h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
            font-family: Arial, sans-serif;
        }
        
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-publicar {
            background: #667eea;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            transition: all 0.3s;
        }
        
        .btn-publicar:hover {
            background: #5568d3;
        }
        
        .publicaciones {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .publicaciones h3 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .publicacion-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .publicacion-item:last-child {
            border-bottom: none;
        }
        
        .publicacion-fecha {
            color: #999;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .publicacion-mensaje {
            color: #333;
            line-height: 1.5;
        }
        
        .usuarios-lista {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .usuarios-lista h3 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .usuario-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border-bottom: 1px solid #eee;
            transition: background 0.3s;
            text-decoration: none;
            color: inherit;
        }
        
        .usuario-item:hover {
            background: #f9f9f9;
        }
        
        .usuario-item:last-child {
            border-bottom: none;
        }
        
        .usuario-imagen-small {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .usuario-info h4 {
            color: #333;
            margin-bottom: 3px;
        }
        
        .usuario-info p {
            color: #666;
            font-size: 14px;
        }
        
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Red Social Simple</h2>
        <a href="index.php?action=logout" class="btn-logout">Cerrar Sesión</a>
    </div>
    
    <div class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="perfil-card">
            <div class="perfil-header">
                <?php if ($_SESSION['usuario_imagen']): ?>
                    <img src="<?php echo htmlspecialchars($_SESSION['usuario_imagen']); ?>" alt="Perfil" class="perfil-imagen">
                <?php else: ?>
                    <img src="https://via.placeholder.com/100" alt="Perfil" class="perfil-imagen">
                <?php endif; ?>
                
                <div class="perfil-info">
                    <h1><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h1>
                    <p><?php echo htmlspecialchars($_SESSION['usuario_email']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="publicacion-form">
            <h3>Crear Publicación</h3>
            <form action="index.php?action=publicar" method="POST" onsubmit="return validarPublicacion()">
                <textarea id="mensaje" name="mensaje" placeholder="¿Qué estás pensando?" required></textarea>
                <button type="submit" class="btn-publicar">Publicar</button>
            </form>
        </div>
        
        <div class="publicaciones">
            <h3>Mis Publicaciones</h3>
            <?php if (empty($publicaciones)): ?>
                <p style="color: #999; text-align: center;">No tienes publicaciones aún.</p>
            <?php else: ?>
                <?php foreach ($publicaciones as $pub): ?>
                    <div class="publicacion-item">
                        <div class="publicacion-fecha">
                            <?php echo date('d/m/Y H:i', strtotime($pub['fecha'])); ?>
                        </div>
                        <div class="publicacion-mensaje">
                            <?php echo htmlspecialchars($pub['mensaje']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="usuarios-lista">
            <h3>Usuarios Registrados</h3>
            <?php foreach ($usuarios as $usuario): ?>
                <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                    <a href="index.php?action=ver_usuario&id=<?php echo $usuario['id']; ?>" class="usuario-item">
                        <?php if ($usuario['imagen']): ?>
                            <img src="<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="Usuario" class="usuario-imagen-small">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/50" alt="Usuario" class="usuario-imagen-small">
                        <?php endif; ?>
                        
                        <div class="usuario-info">
                            <h4><?php echo htmlspecialchars($usuario['usr_name']); ?></h4>
                            <p><?php echo htmlspecialchars($usuario['usr_email']); ?></p>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function validarPublicacion() {
            var mensaje = document.getElementById('mensaje').value.trim();
            if (mensaje === '') {
                alert('El mensaje no puede estar vacío.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
