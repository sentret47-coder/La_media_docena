<?php
// conexion.php - conexión segura a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "la_media_docena";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Error al conectar a la base de datos: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>