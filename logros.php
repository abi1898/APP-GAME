<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["id_usuario"])){

    header("Location: iniciosecion.html");
    exit();

}

$idUsuario = $_SESSION["id_usuario"];


$sqlUsuario = "SELECT usuario, foto
               FROM usuarios
               WHERE id = ?";

$stmtUsuario = $conn->prepare($sqlUsuario);

$stmtUsuario->bind_param("i", $idUsuario);

$stmtUsuario->execute();

$resultadoUsuario = $stmtUsuario->get_result();

$datosUsuario = $resultadoUsuario->fetch_assoc();

$usuario = $datosUsuario["usuario"];


if(!empty($datosUsuario["foto"])){

    $fotoPerfil = $datosUsuario["foto"];

}else{

    $fotoPerfil = "fotos/perfil.png";

}

$sql = "SELECT
            publicaciones.id,
            publicaciones.contenido,
            publicaciones.imagen,
            publicaciones.fecha,
            usuarios.usuario,
            usuarios.foto

        FROM publicaciones

        INNER JOIN usuarios
        ON publicaciones.id_usuario = usuarios.id

        WHERE publicaciones.logro = 1

        ORDER BY publicaciones.id DESC";


$resultado = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Logros | APP GAME</title>

<link rel="stylesheet" href="logros.css">

<link rel="stylesheet" href="daltonismo.css">

</head>


<body>

<div class="menu">


    <a href="perfil.php">

        <div class="boton">

            <img
                src="<?php echo htmlspecialchars($fotoPerfil); ?>"
                alt="Perfil">

        </div>

    </a>


    <a href="logros.php">

        <div class="boton">

            <img
                src="logrosimg.png"
                alt="Logros">

        </div>

    </a>

    <a href="publicaciones.php">

        <div class="boton">

            <img
                src="publicacion.png"
                alt="Publicaciones">

        </div>

    </a>

    <a href="solicitudes.php">

        <div class="boton">

            <img
                src="amigos.png"
                alt="Usuarios">

        </div>

    </a>



    <a href="iniciosecion.html">

        <div class="boton cerrar">

            <img
                src="cerrar.png"
                alt="Cerrar sesión">

        </div>

    </a>


</div>


<div class="contenido">


    <h1 class="titulo">

        🏆 Logros de APP GAME

    </h1>


    <div class="listaLogros">


<?php

if($resultado && $resultado->num_rows > 0){

    while($logro = $resultado->fetch_assoc()){

        if(!empty($logro["foto"])){

            $fotoUsuario = $logro["foto"];

        }else{

            $fotoUsuario = "fotos/perfil.png";

        }

?>

        <div class="logro">

            <div class="usuarioLogro">

                <img
                    src="<?php echo htmlspecialchars($fotoUsuario); ?>"
                    alt="Foto de perfil">

                <strong>

                    <?php
                    echo htmlspecialchars($logro["usuario"]);
                    ?>

                </strong>

            </div>

            <div class="tituloLogro">

                🏆 ¡LOGRO CONSEGUIDO!

            </div>
            <?php

            if(!empty($logro["contenido"])){

            ?>

                <p>

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $logro["contenido"]
                        )
                    );

                    ?>

                </p>

            <?php

            }
            if(!empty($logro["imagen"])){

            ?>

                <img
                    class="imagenLogro"
                    src="<?php echo htmlspecialchars($logro["imagen"]); ?>"
                    alt="Imagen del logro">

            <?php

            }

            ?>
            <small>

                <?php

                echo htmlspecialchars(
                    $logro["fecha"]
                );

                ?>

            </small>


        </div>


<?php

    }

}else{

?>

        <div class="sinLogros">

            🏆 Todavía no hay logros publicados.

        </div>


<?php

}

?>


    </div>


</div>


<script src="daltonismo.js"></script>

</body>

</html>


<?php

$conn->close();

?>