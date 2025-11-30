<?php
// login.php
session_start();
include('conexion.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT id, password_hash, nombre_completo FROM usuarios WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($user = $res->fetch_assoc()) {
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre_completo'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos';
        }
    } else {
        $error = 'Usuario o contraseña incorrectos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Login - La Media Docena Admin</title>
<style>
body{font-family:Arial,Helvetica,sans-serif;background:#f5f7fb;display:flex;align-items:center;justify-content:center;height:100vh;margin:0}
.card{background:#fff;padding:2rem;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.08);width:360px}
h2{margin-bottom:1rem;color:#ff6b35}
label{display:block;margin:0.5rem 0 0.25rem}
input{width:100%;padding:0.6rem;border:1px solid #ddd;border-radius:6px}
button{background:#ff6b35;color:#fff;border:none;padding:0.6rem 1rem;border-radius:6px;cursor:pointer;margin-top:1rem;width:100%}
.error{color:#dc3545;margin-top:0.5rem}
</style>
</head>
<body>
<div class="card">
    <h2>La Media Docena - Admin</h2>
    <form method="POST">
        <label>Usuario</label>
        <input name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? 'admin'); ?>">
        <label>Contraseña</label>
        <input type="password" name="password" required>
        <button type="submit">Iniciar sesión</button>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    </form>
    <p style="font-size:0.85rem;color:#666;margin-top:1rem">Usuario por defecto: <strong>admin</strong> contraseña: <strong>12345</strong></p>
</div>
</body>
</html>
