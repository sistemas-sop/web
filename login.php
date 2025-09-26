<?php
session_start();

// Si ya está logueado, redirigir
if (isset($_SESSION['Sistemas_Aldimark'])) {
    header("Location: admin.php");
    exit;
}

// Validar login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Usuario/Clave fijo (puedes poner los que quieras)
    if ($usuario === "Sistemas_Aldimark" && $clave === "1025528756") {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Usuario o clave incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <img src="img/logo.png" alt="Aldimark" class="logo">
        <h2>Acceso Administrador</h2>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="clave">Clave:</label>
            <input type="password" id="clave" name="clave" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
