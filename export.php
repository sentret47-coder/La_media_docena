<?php
// export.php - export CSV for platillos, clientes, pedidos
include('conexion.php');
$type = $_GET['type'] ?? '';
$filename = $type . '_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);
$out = fopen('php://output', 'w');
if ($type == 'platillos') {
    fputcsv($out, ['id','nombre','descripcion','precio','categoria','disponible','fecha_creacion']);
    $res = $conn->query('SELECT id,nombre,descripcion,precio,categoria,disponible,fecha_creacion FROM platillos');
    while($r=$res->fetch_assoc()) fputcsv($out, $r);
} elseif ($type == 'clientes') {
    fputcsv($out, ['id_cliente','nombre_completo','email','telefono','total_pedidos','ultima_visita']);
    $res = $conn->query('SELECT id_cliente,nombre_completo,email,telefono,total_pedidos,ultima_visita FROM clientes');
    while($r=$res->fetch_assoc()) fputcsv($out, $r);
} elseif ($type == 'pedidos') {
    fputcsv($out, ['id_pedido','cliente_id','cliente_nombre','subtotal','costo_envio','total','estado','fecha_pedido']);
    $res = $conn->query('SELECT id_pedido,cliente_id,cliente_nombre,subtotal,costo_envio,total,estado,fecha_pedido FROM pedidos');
    while($r=$res->fetch_assoc()) fputcsv($out, $r);
} else {
    fputcsv($out, ['error','Tipo no válido']);
}
fclose($out);
exit;
?>