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
            publicaciones.logro,
            usuarios.usuario,
            usuarios.foto

        FROM publicaciones

        INNER JOIN usuarios
        ON publicaciones.id_usuario = usuarios.id

        ORDER BY publicaciones.id DESC";


$resultado = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Publicaciones | APP GAME</title>

<link rel="stylesheet" href="publicaciones.css">

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

  <a href="logros.php">
    <div class="boton">
        <img src="logrosimg.png" alt="Logros">
    </div>
</a>

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
    <div class="crearPublicacion">


        <h2>Crear publicación</h2>


        <form
            action="publicar.php"
            method="POST"
            enctype="multipart/form-data">
            <textarea
                name="contenido"
                placeholder="¿Qué quieres compartir?"
                rows="4"></textarea>

            <input
                type="file"
                name="imagen"
                accept="image/*">
            <label class="checkLogro">

                <input
                    type="checkbox"
                    name="logro"
                    value="1">

                🏆 Publicar también como logro

            </label>
            <button type="submit">

                Publicar

            </button>


        </form>


    </div>
    <div class="listaPublicaciones">


<?php

if($resultado && $resultado->num_rows > 0){

    while($publicacion = $resultado->fetch_assoc()){
        if(!empty($publicacion["foto"])){

            $fotoUsuario = $publicacion["foto"];

        }else{

            $fotoUsuario = "fotos/perfil.png";

        }

?>
        <div class="publicacion">

            <div class="usuarioPublicacion">


                <img
                    src="<?php
                    echo htmlspecialchars($fotoUsuario);
                    ?>"
                    alt="Foto de perfil">


                <strong>

                    <?php

                    echo htmlspecialchars(
                        $publicacion["usuario"]
                    );

                    ?>

                </strong>


            </div>
            <?php

            if($publicacion["logro"] == 1){

            ?>

                <div class="tituloLogro">

                    🏆 LOGRO

                </div>

            <?php

            }

            ?>
            <p>

                <?php

                echo nl2br(
                    htmlspecialchars(
                        $publicacion["contenido"]
                    )
                );

                ?>

            </p>
            <?php

            if(!empty($publicacion["imagen"])){

            ?>

                <img
                    class="imagenPublicacion"
                    src="<?php
                    echo htmlspecialchars(
                        $publicacion["imagen"]
                    );
                    ?>"
                    alt="Imagen de publicación">

            <?php

            }

            ?>

            <small>

                <?php

                echo htmlspecialchars(
                    $publicacion["fecha"]
                );

                ?>

            </small>


        </div>


<?php

    }

}else{

?>


        <p class="sinPublicaciones">

            Todavía no hay publicaciones.

        </p>


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
```
