<?php
// platillo_action.php - actions: add, edit, delete
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
include('conexion.php');
$action = $_GET['action'] ?? '';
if ($action == 'add' && $_SERVER['REQUEST_METHOD']=='POST') {
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
    $descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $categoria = $conn->real_escape_string($_POST['categoria'] ?? '');
    $disponible = isset($_POST['disponible']) ? intval($_POST['disponible']) : 1;
    $stmt = $conn->prepare('INSERT INTO platillos (nombre, descripcion, precio, categoria, disponible) VALUES (?,?,?,?,?)');
    $stmt->bind_param('ssdsi', $nombre, $descripcion, $precio, $categoria, $disponible);
    if ($stmt->execute()) { header('Location: index.php'); exit; } else { echo 'Error: '.$conn->error; }
}
if ($action == 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD']=='POST') {
    $id = intval($_GET['id']);
    $nombre = $conn->real_escape_string($_POST['nombre'] ?? '');
    $descripcion = $conn->real_escape_string($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $categoria = $conn->real_escape_string($_POST['categoria'] ?? '');
    $disponible = isset($_POST['disponible']) ? intval($_POST['disponible']) : 1;
    $stmt = $conn->prepare('UPDATE platillos SET nombre=?, descripcion=?, precio=?, categoria=?, disponible=? WHERE id=?');
    $stmt->bind_param('ssdsii', $nombre, $descripcion, $precio, $categoria, $disponible, $id);
    if ($stmt->execute()) { header('Location: index.php'); exit; } else { echo 'Error: '.$conn->error; }
}
if ($action == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare('DELETE FROM platillos WHERE id=?');
    $stmt->bind_param('i',$id);
    if ($stmt->execute()) { header('Location: index.php'); exit; } else { echo 'Error: '.$conn->error; }
}
?>