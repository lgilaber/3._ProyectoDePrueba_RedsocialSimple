<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Red Social Simple</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 20px;
        }
        
        p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #764ba2;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #653a8f;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido a Red Social Simple</h1>
        <p>Conecta con tus amigos y comparte tus pensamientos. Crea una cuenta o inicia sesión para comenzar.</p>
        
        <div class="buttons">
            <a href="index.php?action=register" class="btn btn-primary">Registrarse</a>
            <a href="index.php?action=login" class="btn btn-secondary">Iniciar Sesión</a>
        </div>
    </div>
</body>
</html>
