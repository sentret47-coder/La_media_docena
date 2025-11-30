<?php
// index.php - Panel protegido
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include('conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Panel - La Media Docena</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{--primary:#ff6b35;--dark:#1a1a2e;--light:#fff}
body{font-family:Arial,Helvetica,sans-serif;background:#f2f5f8;margin:0;color:#333}
.header{background:var(--primary);color:#fff;padding:1rem 1.5rem;display:flex;justify-content:space-between;align-items:center}
.container{max-width:1100px;margin:20px auto;padding:0 1rem}
.sidebar{width:240px;float:left;background:#fff;padding:1rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.05)}
.main{margin-left:270px}
.card{background:#fff;padding:1rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.04);margin-bottom:1rem}
.table{width:100%;border-collapse:collapse}
.table th{background:var(--primary);color:#fff;padding:0.6rem;text-align:left}
.table td{padding:0.6rem;border-bottom:1px solid #eee}
.btn{background:var(--primary);color:#fff;padding:0.5rem 0.8rem;border-radius:6px;border:none;cursor:pointer}
.logout{background:#fff;color:#ff6b35;padding:0.4rem .6rem;border-radius:6px;border:1px solid rgba(255,255,255,0.2);text-decoration:none}
</style>
</head>
<body>
<div class="header">
    <div style="font-weight:bold">LA MEDIA DOCENA 🥯 - Panel</div>
    <div>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?> — <a class="logout" href="logout.php">Cerrar sesión</a></div>
</div>

<div class="container">
    <div class="sidebar card">
        <h3>Menú</h3>
        <ul style="list-style:none;padding-left:0">
            <li><a href="index.php?view=platillos">Platillos</a></li>
            <li><a href="index.php?view=pedidos">Pedidos</a></li>
            <li><a href="index.php?view=clientes">Clientes</a></li>
            <li><a href="index.php?view=export">Exportar</a></li>
            <li><a href="install.php">(Re)Instalar DB</a></li>
        </ul>
    </div>

    <div class="main">
        <?php if (!isset($_GET['view']) || $_GET['view']=='platillos'): ?>
            <div class="card">
                <h2>Platillos <button class="btn" onclick="window.location='index.php?view=add_platillo'">Agregar</button></h2>
                <?php
                $res = $conn->query('SELECT * FROM platillos ORDER BY id DESC');
                if ($res->num_rows==0) echo '<p>No hay platillos</p>'; else {
                    echo '<table class="table"><tr><th>Nombre</th><th>Precio</th><th>Categoria</th><th>Disponible</th><th>Acciones</th></tr>';
                    while($r=$res->fetch_assoc()){
                        echo '<tr><td>'.htmlspecialchars($r['nombre']).'</td><td>$'.number_format($r['precio'],2).'</td><td>'.htmlspecialchars($r['categoria']).'</td><td>'.($r['disponible']? '✅':'❌').'</td><td><a href="index.php?view=edit_platillo&id='.$r['id'].'">Editar</a> | <a href="platillo_action.php?action=delete&id='.$r['id'].'" onclick="return confirm(\\'Eliminar?\\')">Eliminar</a></td></tr>';
                    }
                    echo '</table>';
                }
                ?>
            </div>
        <?php elseif ($_GET['view']=='add_platillo'): ?>
            <div class="card">
                <h2>Agregar Platillo</h2>
                <form method="post" action="platillo_action.php?action=add">
                    <label>Nombre</label><input name="nombre" required><br><br>
                    <label>Descripción</label><textarea name="descripcion"></textarea><br><br>
                    <label>Precio</label><input name="precio" type="number" step="0.01" required><br><br>
                    <label>Categoría</label><input name="categoria"><br><br>
                    <label>Disponible</label><select name="disponible"><option value="1">Sí</option><option value="0">No</option></select><br><br>
                    <button class="btn" type="submit">Guardar</button>
                </form>
            </div>
        <?php elseif ($_GET['view']=='edit_platillo' && isset($_GET['id'])): 
            $id = intval($_GET['id']);
            $stmt = $conn->prepare('SELECT * FROM platillos WHERE id=?');
            $stmt->bind_param('i',$id); $stmt->execute(); $res = $stmt->get_result();
            $item = $res->fetch_assoc();
        ?>
            <div class="card">
                <h2>Editar Platillo</h2>
                <form method="post" action="platillo_action.php?action=edit&id=<?php echo $id; ?>">
                    <label>Nombre</label><input name="nombre" required value="<?php echo htmlspecialchars($item['nombre']); ?>"><br><br>
                    <label>Descripción</label><textarea name="descripcion"><?php echo htmlspecialchars($item['descripcion']); ?></textarea><br><br>
                    <label>Precio</label><input name="precio" type="number" step="0.01" required value="<?php echo htmlspecialchars($item['precio']); ?>"><br><br>
                    <label>Categoría</label><input name="categoria" value="<?php echo htmlspecialchars($item['categoria']); ?>"><br><br>
                    <label>Disponible</label><select name="disponible"><option value="1" <?php if($item['disponible']) echo 'selected'; ?>>Sí</option><option value="0" <?php if(!$item['disponible']) echo 'selected'; ?>>No</option></select><br><br>
                    <button class="btn" type="submit">Actualizar</button>
                </form>
            </div>
        <?php elseif ($_GET['view']=='pedidos'): ?>
            <div class="card">
                <h2>Pedidos</h2>
                <?php
                $res = $conn->query('SELECT * FROM pedidos ORDER BY fecha_pedido DESC');
                if ($res->num_rows==0) echo '<p>No hay pedidos</p>'; else {
                    echo '<table class="table"><tr><th>ID</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Fecha</th></tr>';
                    while($r=$res->fetch_assoc()){
                        echo '<tr><td>'.$r['id_pedido'].'</td><td>'.htmlspecialchars($r['cliente_nombre']).'</td><td>$'.number_format($r['total'],2).'</td><td>'.htmlspecialchars($r['estado']).'</td><td>'.$r['fecha_pedido'].'</td></tr>';
                    }
                    echo '</table>';
                }
                ?>
            </div>
        <?php elseif ($_GET['view']=='clientes'): ?>
            <div class="card">
                <h2>Clientes</h2>
                <?php
                $res = $conn->query('SELECT * FROM clientes ORDER BY nombre_completo');
                if ($res->num_rows==0) echo '<p>No hay clientes</p>'; else {
                    echo '<table class="table"><tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Total Pedidos</th></tr>';
                    while($r=$res->fetch_assoc()){
                        echo '<tr><td>'.htmlspecialchars($r['nombre_completo']).'</td><td>'.htmlspecialchars($r['email']).'</td><td>'.htmlspecialchars($r['telefono']).'</td><td>'.intval($r['total_pedidos']).'</td></tr>';
                    }
                    echo '</table>';
                }
                ?>
            </div>
        <?php elseif ($_GET['view']=='export'): ?>
            <div class="card">
                <h2>Exportar Datos</h2>
                <p>
                    <a href="export.php?type=platillos" class="btn">Exportar Platillos (CSV)</a>
                    <a href="export.php?type=clientes" class="btn">Exportar Clientes (CSV)</a>
                    <a href="export.php?type=pedidos" class="btn">Exportar Pedidos (CSV)</a>
                </p>
                <p style="margin-top:1rem;color:#666">Nota: las exportaciones CSV se abren con Excel. Para generar PDF en servidor se puede instalar <em>dompdf</em> o <em>tcpdf</em>. También puedes usar la opción de imprimir del navegador para guardar en PDF.</p>
            </div>
        <?php endif; ?>
    </div>
    <div style="clear:both"></div>
</div>
</body>
</html>
