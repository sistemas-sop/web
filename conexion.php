<?php
// conexion.php - ajusta credenciales si es necesario
$servername = "localhost";
$username   = "root";
$password   = ""; // si tienes contraseña ponla
$dbname     = "tickets";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
