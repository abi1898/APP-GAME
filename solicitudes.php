<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["id_usuario"])){

    header("Location: iniciosecion.html");
    exit();

}

$idUsuario = $_SESSION["id_usuario"];
if(isset($_SESSION["foto"]) && $_SESSION["foto"] != ""){

    $fotoPerfil = $_SESSION["foto"];

}else{

    $fotoPerfil = "fotos/perfil.png";

}
$sql = "SELECT id, usuario, foto
        FROM usuarios
        WHERE id != '$idUsuario'";

$resultado = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>APP GAME - Seguidores</title>

<link rel="stylesheet" href="solicitudes.css">

<link rel="stylesheet" href="daltonismo.css">

</head>


<body>
<div class="menu">
  <a href="perfil.php" class="icono">

        <div class="boton">

            <img src="<?php echo htmlspecialchars($fotoPerfil); ?>">

        </div>

    <a href="logros.php">
    <div class="boton">
        <img src="logrosimg.png" alt="Logros">
    </div>
</a>
  <a href="publicaciones.php">
    <a href="solicitudes.php">

        <div class="boton">

            <img src="amigos.png">

        </div>

    </a>
    <a href="iniciosecion.html">

        <div class="boton cerrar">

            <img src="cerrar.png">

        </div>

    </a>


</div>
<div class="contenido">


<?php

if($resultado && $resultado->num_rows > 0){

    while($usuario = $resultado->fetch_assoc()){

        if(empty($usuario["foto"])){

            $fotoUsuario = "fotos/perfil.png";

        }else{

            $fotoUsuario = $usuario["foto"];

        }

        $idUsuarioMostrado = $usuario["id"];

        $comprobar = "SELECT id
                      FROM seguidores
                      WHERE id_seguidor = '$idUsuario'
                      AND id_seguido = '$idUsuarioMostrado'";

        $yaSigue = $conn->query($comprobar);

?>
    <div class="usuario">
<img
    src="<?php echo htmlspecialchars($fotoUsuario); ?>"
    alt="Foto de <?php echo htmlspecialchars($usuario["usuario"]); ?>"
    class="fotoUsuario"
>


        <h2>

            <?php echo htmlspecialchars($usuario["usuario"]); ?>

        </h2>


        <?php

        if($yaSigue && $yaSigue->num_rows > 0){

        ?>

            <button type="button" disabled>

                Siguiendo

            </button>


        <?php

        }else{

        ?>


            <form action="seguir.php" method="POST">

                <input
                    type="hidden"
                    name="id_usuario"
                    value="<?php echo $usuario["id"]; ?>"
                >


                <button type="submit">

                    Seguir

                </button>

            </form>
        <?php

        }

        ?>


    </div>


<?php

    }

}else{

?>

    <p style="
        color:white;
        font-size:25px;
        text-align:center;
        width:100%;
    ">

        No hay otros usuarios registrados.

    </p>

<?php

}

?>


</div>


<script src="solicitudes.js"></script>

<script src="daltonismo.js"></script>


</body>

</html>

<?php

$conn->close();

?>