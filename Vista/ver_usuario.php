<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($usuario['usr_name']); ?> - Red Social Simple</title>
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
        
        .btn-volver {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-volver:hover {
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
        
        .publicaciones {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Red Social Simple</h2>
        <a href="index.php?action=perfil" class="btn-volver">Volver a Mi Perfil</a>
    </div>
    
    <div class="container">
        <div class="perfil-card">
            <div class="perfil-header">
                <?php if ($usuario['imagen']): ?>
                    <img src="<?php echo htmlspecialchars($usuario['imagen']); ?>" alt="Perfil" class="perfil-imagen">
                <?php else: ?>
                    <img src="https://via.placeholder.com/100" alt="Perfil" class="perfil-imagen">
                <?php endif; ?>
                
                <div class="perfil-info">
                    <h1><?php echo htmlspecialchars($usuario['usr_name']); ?></h1>
                    <p><?php echo htmlspecialchars($usuario['usr_email']); ?></p>
                </div>
            </div>
        </div>
        
        <div class="publicaciones">
            <h3>Publicaciones de <?php echo htmlspecialchars($usuario['usr_name']); ?></h3>
            <?php if (empty($publicaciones)): ?>
                <p style="color: #999; text-align: center;">Este usuario no tiene publicaciones aún.</p>
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
    </div>
</body>
</html>
