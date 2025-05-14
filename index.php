<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'Banco4';
$user = 'root'; // o 'yael' si corresponde
$password = 'Dekuyojm14$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesar la selección de tabla
$search = $_GET['buscar'] ?? '';
$tabla = $_GET['tabla'] ?? 'Clientes';  // Por defecto mostrar la tabla de Clientes
$sql = "";

switch ($tabla) {
    case 'Clientes':
        $sql = "SELECT * FROM Clientes WHERE nombre LIKE :buscar OR apellido LIKE :buscar";
        break;
    case 'Cuentas':
        $sql = "SELECT c.cuenta_id, cl.nombre, cl.apellido, c.tipo_cuenta, c.saldo_actual, c.estado 
                FROM Cuentas c 
                JOIN Clientes cl ON c.cliente_id = cl.cliente_id 
                WHERE cl.nombre LIKE :buscar OR cl.apellido LIKE :buscar";
        break;
    case 'Movimientos':
        $sql = "SELECT m.movimiento_id, c.cuenta_id, m.fecha_movimiento, m.tipo_movimiento, m.monto, m.descripcion
                FROM Movimientos m
                JOIN Cuentas c ON m.cuenta_id = c.cuenta_id
                WHERE c.cuenta_id LIKE :buscar";
        break;
    case 'Tarjetas':
        $sql = "SELECT t.tarjeta_id, cl.nombre, cl.apellido, t.tipo_tarjeta, t.estado, t.fecha_emision
                FROM Tarjetas t
                JOIN Clientes cl ON t.cliente_id = cl.cliente_id
                WHERE cl.nombre LIKE :buscar OR cl.apellido LIKE :buscar";
        break;
    default:
        $sql = "SELECT * FROM Clientes WHERE nombre LIKE :buscar OR apellido LIKE :buscar";
        break;
}

$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$search%"]);
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Datos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            width: 80%;
            max-width: 1000px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        label {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        select, input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 50%;
            margin: 0 auto;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            align-self: center;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
        }

        td {
            font-size: 14px;
            color: #555;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .no-results {
            text-align: center;
            color: #888;
            font-size: 18px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Consulta de Datos</h2>
        <form method="get">
            <label for="tabla">Selecciona la tabla:</label>
            <select name="tabla" id="tabla">
                <option value="Clientes" <?= $tabla == 'Clientes' ? 'selected' : '' ?>>Clientes</option>
                <option value="Cuentas" <?= $tabla == 'Cuentas' ? 'selected' : '' ?>>Cuentas</option>
                <option value="Movimientos" <?= $tabla == 'Movimientos' ? 'selected' : '' ?>>Movimientos</option>
                <option value="Tarjetas" <?= $tabla == 'Tarjetas' ? 'selected' : '' ?>>Tarjetas</option>
            </select>
            <label for="buscar">Buscar:</label>
            <input type="text" name="buscar" id="buscar" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Buscar</button>
        </form>

        <?php if ($resultado): ?>
            <table>
                <tr>
                    <?php 
                    // Definir encabezados según la tabla seleccionada
                    if ($tabla == 'Clientes') {
                        echo '<th>ID</th><th>Nombre</th><th>Apellido</th><th>Teléfono</th><th>Correo</th><th>Fecha de Registro</th><th>Estado</th>';
                    } elseif ($tabla == 'Cuentas') {
                        echo '<th>ID Cuenta</th><th>Cliente</th><th>Tipo de Cuenta</th><th>Saldo Actual</th><th>Estado</th>';
                    } elseif ($tabla == 'Movimientos') {
                        echo '<th>ID Movimiento</th><th>ID Cuenta</th><th>Fecha</th><th>Tipo</th><th>Monto</th><th>Descripción</th>';
                    } elseif ($tabla == 'Tarjetas') {
                        echo '<th>ID Tarjeta</th><th>Cliente</th><th>Tipo de Tarjeta</th><th>Estado</th><th>Fecha de Emisión</th>';
                    }
                    ?>
                </tr>
                <?php foreach ($resultado as $row): ?>
                    <tr>
                        <?php foreach ($row as $column): ?>
                            <td><?= htmlspecialchars($column) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p class="no-results">No se encontraron resultados.</p>
        <?php endif; ?>
    </div>
</body>
</html>
