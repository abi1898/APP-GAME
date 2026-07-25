<?php

session_start();
include("conexion.php");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(!isset($_SESSION["id_usuario"])){

    header("Location: iniciosecion.html");
    exit();

}

$idUsuario = $_SESSION["id_usuario"];
$sql = "SELECT usuario, foto
        FROM usuarios
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $idUsuario);

$stmt->execute();

$resultado = $stmt->get_result();


if($resultado->num_rows == 0){

    session_destroy();

    header("Location: iniciosecion.html");
    exit();

}


$datosUsuario = $resultado->fetch_assoc();


$usuario = $datosUsuario["usuario"];


if(!empty($datosUsuario["foto"])){

    $foto = $datosUsuario["foto"];

}else{

    $foto = "fotos/perfil.png";

}
$_SESSION["usuario"] = $usuario;
$_SESSION["foto"] = $foto;

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>MENU-APP GAME</title>

<link rel="stylesheet" href="menu.css">

<link rel="stylesheet" href="daltonismo.css">

</head>


<body>


<div class="contenedor">


    <div class="menu">
        <a href="perfil.php" class="icono">

            <img src="<?php echo htmlspecialchars($foto); ?>" alt="Perfil">

        </a>
        <a href="logros.html" class="icono">

            <img src="logrosimg.png" alt="Logros">

        </a>
      <a href="publicaciones.php" class="icono">
    <img src="publicacion.png" alt="Publicaciones">
</a>
        <a href="solicitudes.php" class="icono">

            <img src="amigos.png" alt="Amigos">

        </a>
        <a href="iniciosecion.html" class="icono">

            <img src="cerrar.png" alt="Cerrar sesión">

        </a>


    </div>


    <div class="principal">
        <img
            src="<?php echo htmlspecialchars($foto); ?>"
            class="fotoPerfil"
            alt="Foto de perfil"
        >
        <div class="nombre">

            <?php echo htmlspecialchars($usuario); ?>

        </div>

        <div class="datos">

            Jugador de APP GAME

        </div>
        <button class="boton">

            Seguidores

        </button>


        <button class="boton">

            Seguidos

        </button>


        <button class="boton">

            Publicaciones

        </button>


    </div>


</div>


<script src="menu.js"></script>

<script src="daltonismo.js"></script>


</body>

</html>


<?php

$stmt->close();

$conn->close();

?>