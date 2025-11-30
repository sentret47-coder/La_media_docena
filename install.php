<?php
// install.php - ejecuta el script SQL para crear tablas y crear admin inicial
include('conexion.php');
$sql = file_get_contents('la_media_docena.sql');
if ($conn->multi_query($sql)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    echo "Estructura creada correctamente.\n";
} else {
    echo "Error al ejecutar script SQL: " . $conn->error;
    exit;
}

// Crear usuario admin inicial si no existe
$admin = 'admin';
$password = '12345'; // contraseña por defecto: cambia en producción
$stmt = $conn->prepare('SELECT id FROM usuarios WHERE username = ?');
$stmt->bind_param('s', $admin);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $conn->prepare('INSERT INTO usuarios (username, password_hash, nombre_completo, email, created_at) VALUES (?, ?, ?, ?, NOW())');
    $nombre = 'Admin La Media Docena';
    $email = 'admin@lamediadocena.local';
    $ins->bind_param('ssss', $admin, $hash, $nombre, $email);
    $ins->execute();
    echo "Usuario admin creado: usuario=admin contraseña=12345\n";
} else {
    echo "Usuario admin ya existe.\n";
}
?>