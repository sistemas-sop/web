<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// --- Cierre de sesión por inactividad ---
$inactividad = 600; // 600 segundos = 10 minutos
if (isset($_SESSION['admin_logged'])) {
    if (isset($_SESSION['ultimo_acceso'])) {
        $tiempo_inactivo = time() - $_SESSION['ultimo_acceso'];
        if ($tiempo_inactivo > $inactividad) {
            session_unset();
            session_destroy();
            header("Location: admin.php");
            exit;
        }
    }
    $_SESSION['ultimo_acceso'] = time();
}
include 'conexion.php';

// --- LOGIN ---
if (!isset($_SESSION['admin_logged'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario'])) {
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];

        // Cambia aquí tu usuario y clave de admin
        if ($usuario === "Sistemas_Aldimark" && $clave === "1025528756") {
            $_SESSION['admin_logged'] = true;
            header("Location: admin.php");
            exit;
        } else {
            $error = "❌ Usuario o clave incorrectos ❌";
        }
    }

    // Formulario login
    //
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <script>
    // Cierre de sesión visual automático tras 10 minutos (600000 ms) de inactividad
    let tiempoInactividad = 600000; // 10 minutos en milisegundos
    let timeout;
    function resetInactividad() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            window.location.href = 'logout.php';
        }, tiempoInactividad);
    }
    document.addEventListener('DOMContentLoaded', resetInactividad);
    document.addEventListener('mousemove', resetInactividad);
    document.addEventListener('keydown', resetInactividad);
    document.addEventListener('click', resetInactividad);
    </script>
        <meta charset="UTF-8">
        <title>Login Admin</title>
        <link rel="stylesheet" href="style.css">
        <style>
        body {
            background: linear-gradient(135deg, #fff 60%, #ffcccc 100%);
            min-height: 100vh;
            margin: 0;
            position: relative;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('img/logo.png') center center/60% no-repeat;
            opacity: 0.08;
            z-index: 0;
        }
        .container {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px 0 rgba(180,0,0,0.10), 0 1.5px 4px 0 rgba(180,0,0,0.10);
            padding: 32px 28px 28px 28px;
            max-width: 400px;
            margin: 60px auto 40px auto;
            position: relative;
            z-index: 1;
        }
        .container h2 {
            color: #b30000;
            margin-bottom: 18px;
            font-size: 1.6rem;
            letter-spacing: 1px;
            text-align: center;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 7px;
            border: 1px solid #ccc;
            margin-bottom: 18px;
            font-size: 1.1rem;
            background: #f8f8f8;
            color: #222;
        }
        label {
            color: #b30000;
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }
        button {
            background: linear-gradient(90deg, #b30000 60%, #ff6666 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 10px 0;
            width: 100%;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.2s, transform 0.2s;
        }
        button:hover {
            background: linear-gradient(90deg, #ff6666 60%, #b30000 100%);
            transform: scale(1.04);
        }
        @media (max-width: 600px) {
            .container {
                max-width: 98vw;
                margin: 20px auto;
                padding: 10px;
            }
            body::before {
                background-size: 90vw;
            }
        }
        </style>
    </head>
    <body class="animar">
        <div class="container">
            <h2> Acceso Administrador</h2>
            <?php if (isset($error)) echo '<p style="color:red; text-align:center; margin-bottom:10px; font-weight:bold;">'.$error.'</p>'; ?>
            <form method="POST">
                <label for="usuario">Usuario:</label>
                <input type="text" name="usuario" id="usuario" required autocomplete="username">
                <label for="clave">Clave:</label>
                <input type="password" name="clave" id="clave" required autocomplete="current-password">
                <button type="submit">Ingresar</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// --- EXPORTAR A EXCEL ---
if (isset($_GET['export'])) {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=tickets.xls");
    $result = $conn->query("SELECT * FROM tickets ORDER BY id DESC");
    echo "ID\tMes\tFecha\tÁrea\tCentro Costo\tResponsable\tTema\tSolicitud\tSolución\tMétodo\tFecha Rta\tEstado\n";
    while ($row = $result->fetch_assoc()) {
        echo $row['id']."\t".$row['mes']."\t".$row['fecha']."\t".$row['area']."\t".$row['centro_costo']."\t".
             $row['quien_solicita']."\t".$row['tema']."\t".$row['solicitud']."\t".$row['solucion']."\t".
             $row['metodo']."\t".$row['fecha_rta']."\t".$row['estado']."\n";
    }
    exit;
}

// --- VISTA ADMIN ---
$result = $conn->query("SELECT * FROM tickets ORDER BY id DESC") or die("Error en la consulta: " . $conn->error);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff 60%, #ffcccc 100%);
            font-family: Tahoma, Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('/tickets/img/logo.png') center center/70% no-repeat;
            opacity: 0.13;
            z-index: 0;
            pointer-events: none;
        }
        .admin-panel {
            position: relative;
            z-index: 1;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px 0 rgba(180,0,0,0.10), 0 1.5px 4px 0 rgba(180,0,0,0.10);
            padding: 32px 28px 28px 28px;
            max-width: 98vw;
            margin: 40px auto 40px auto;
        }
        .admin-panel {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px 0 rgba(180,0,0,0.10), 0 1.5px 4px 0 rgba(180,0,0,0.10);
            padding: 32px 28px 28px 28px;
            max-width: 98vw;
            margin: 40px auto 40px auto;
        }
        .admin-panel h2 {
            color: #b30000;
            margin-bottom: 18px;
            font-size: 2.1rem;
            letter-spacing: 1px;
        }
        .admin-panel a button {
            background: linear-gradient(90deg, #b30000 60%, #ff6666 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 18px;
            margin: 0 8px 18px 0;
            font-size: 1rem;
            font-family: Tahoma, Arial, sans-serif;
            cursor: pointer;
            box-shadow: 0 2px 8px 0 rgba(180,0,0,0.10);
            transition: background 0.2s, transform 0.2s;
        }
        .admin-panel a button:hover {
            background: linear-gradient(90deg, #ff6666 60%, #b30000 100%);
            transform: translateY(-2px) scale(1.04);
        }
        .admin-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin-top: 18px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px 0 rgba(180,0,0,0.07);
        }
        .admin-table th, .admin-table td {
            padding: 10px 8px;
            text-align: left;
        }
        .admin-table th {
            background: linear-gradient(90deg, #b30000 60%, #ff6666 100%);
            color: #fff;
            font-weight: bold;
            border-bottom: 2px solid #b30000;
        }
        .admin-table tr:nth-child(even) {
            background: #fff5f5;
        }
        .admin-table tr:hover {
            background: #ffeaea;
        }
        .admin-table td {
            border-bottom: 1px solid #f0b3b3;
            font-size: 0.98rem;
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <h2> Panel Administrador - Tickets</h2>
        <a href="admin.php?export=1"><button>⬇ Exportar a Excel</button></a>
        <a href="logout.php"><button>🚪 Cerrar sesión</button></a>
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Mes</th>
                <th>Fecha</th>
                <th>Área</th>
                <th>Centro de Costo</th>
                <th>Responsable</th>
                <th>Tema</th>
                <th>Solicitud</th>
                <th>Solución</th>
                <th>Método</th>
                <th>Fecha Rta</th>
                <th>Estado</th>
            </tr>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['mes'] ?></td>
                    <td><?= $row['fecha'] ?></td>
                    <td><?= $row['area'] ?></td>
                    <td><?= $row['centro_costo'] ?></td>
                    <td><?= $row['quien_solicita'] ?></td>
                    <td><?= $row['tema'] ?></td>
                    <td><?= $row['solicitud'] ?></td>
                    <td><?= $row['solucion'] ?></td>
                    <td><?= $row['metodo'] ?></td>
                    <td><?= $row['fecha_rta'] ?></td>
                    <td><?= $row['estado'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>