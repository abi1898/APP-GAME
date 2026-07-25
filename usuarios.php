<?php
session_start();
include("conexion.php");
if(!isset($_SESSION["usuario"])){
    header("Location: iniciosecion.html");
    exit();
}

$usuarioActual = $_SESSION["usuario"];
$sql = "SELECT * FROM usuarios WHERE usuario != '$usuarioActual'";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios | APP GAME</title>
<link rel="stylesheet" href="perfil.css">
</head>
<body>
<h1>Usuarios de APP GAME</h1>
<div class="usuarios">
<?php
while($usuario = $resultado->fetch_assoc()){
?>
<div class="usuario">
<img src="<?php echo $usuario['foto']; ?>" width="100">
<h2>
<?php echo $usuario['usuario']; ?>
</h2>
<form action="seguir.php" method="POST">
<input 
type="hidden"
name="id_usuario"
value="<?php echo $usuario['id']; ?>">
<button class="boton">
Seguir
</button>
</form>
</div>
<?php
}
?>
</div>
</body>
</html>