<?php
session_start();
$host = 'localhost';
$dbname = 'user';
$user = 'root';
$password = 'Dekuyojm14$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE user = :username AND password = :password');

    $stmt->execute(['username' => $username, 'password' => $pass]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user['ncompleto'];
        header('Location: 4f/index.php');
        exit;
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Administrador - Banco</title>
    <style>
        body {
            background-color: #f1f1f1;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 350px;
        }
        .login-box h2 {
            text-align: center;
            color: #1a237e;
            margin-bottom: 20px;
        }
        .login-box .icon {
            text-align: center;
            font-size: 50px;
            color: #ffab00;
            margin-bottom: 10px;
        }
        .login-box label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
            color: #333;
        }
        .login-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #bbb;
            border-radius: 5px;
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .login-box button {
            width: 100%;
            background-color: #1a237e;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .login-box button:hover {
            background-color: #0d1b5d;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="icon">🏦</div>
        <h2>Portal de Administración Bancaria</h2>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="post">
            <label for="username">Usuario</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>

