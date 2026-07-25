<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["usuario"]) || !isset($_SESSION["id_usuario"])){

    header("Location: iniciosecion.html");
    exit();

}

$usuario = $_SESSION["usuario"];
$idUsuario = $_SESSION["id_usuario"];

if(isset($_SESSION["foto"]) && $_SESSION["foto"] != ""){

    $foto = $_SESSION["foto"];

}else{

    $foto = "fotos/perfil.png";

}

$seccion = isset($_GET["seccion"]) ? $_GET["seccion"] : "";

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Perfil | APP GAME</title>

<link rel="stylesheet" href="menu.css">
<link rel="stylesheet" href="perfil.css">
<link rel="stylesheet" href="daltonismo.css">

</head>

<body>

<div class="contenedor">

    <div class="menu">

        <a href="perfil.php" class="icono">
            <img src="<?php echo htmlspecialchars($foto); ?>" alt="Perfil">
        </a>

        <a href="logros.php" class="icono">
            <img src="logrosimg.png" alt="Logros">
        </a>

        <a href="publicaciones.php" class="icono">
            <img src="publicacion.png" alt="Publicaciones">
        </a>

        <a href="solicitudes.php" class="icono">
            <img src="amigos.png" alt="Usuarios">
        </a>

        <a href="iniciosecion.html" class="icono">
            <img src="cerrar.png" alt="Cerrar sesión">
        </a>

    </div>


    <div class="perfil">

        <img
            src="<?php echo htmlspecialchars($foto); ?>"
            alt="Foto de perfil"
            class="fotoPerfil"
        >

        <h2>
            <?php echo htmlspecialchars($usuario); ?>
        </h2>


        <form
            action="cambiarfoto.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <input
                type="file"
                id="foto"
                name="foto"
                accept="image/*"
                required
                style="display:none;"
            >

            <label for="foto" class="boton">
                Seleccionar foto
            </label>

            <button type="submit" class="boton">
                Cambiar foto de perfil
            </button>

        </form>


        <div class="seguimiento">

            <a href="perfil.php?seccion=seguidores">
                <button type="button" class="boton">
                    Seguidores
                </button>
            </a>

            <a href="perfil.php?seccion=seguidos">
                <button type="button" class="boton">
                    Seguidos
                </button>
            </a>

            <a href="perfil.php?seccion=publicaciones">
                <button type="button" class="boton">
                    Publicaciones
                </button>
            </a>

        </div>


        <div class="publicaciones">


<?php
if($seccion == "seguidores"){

?>

    <h2>Seguidores</h2>

<?php

$sql = "SELECT usuarios.usuario, usuarios.foto
        FROM seguidores
        INNER JOIN usuarios
        ON seguidores.id_seguidor = usuarios.id
        WHERE seguidores.id_seguido = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

$resultado = $stmt->get_result();

if($resultado && $resultado->num_rows > 0){

    while($persona = $resultado->fetch_assoc()){

        if(empty($persona["foto"])){

            $fotoPersona = "fotos/perfil.png";

        }else{

            $fotoPersona = $persona["foto"];

        }

?>

        <div class="usuario">

            <img
                src="<?php echo htmlspecialchars($fotoPersona); ?>"
                class="fotoUsuario"
                alt="Foto de perfil"
            >

            <h3>
                <?php echo htmlspecialchars($persona["usuario"]); ?>
            </h3>

        </div>

<?php

    }

}else{

    echo "<p>Aún no tienes seguidores.</p>";

}
}elseif($seccion == "seguidos"){

?>

    <h2>Seguidos</h2>

<?php

$sql = "SELECT usuarios.usuario, usuarios.foto
        FROM seguidores
        INNER JOIN usuarios
        ON seguidores.id_seguido = usuarios.id
        WHERE seguidores.id_seguidor = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

$resultado = $stmt->get_result();

if($resultado && $resultado->num_rows > 0){

    while($persona = $resultado->fetch_assoc()){

        if(empty($persona["foto"])){

            $fotoPersona = "fotos/perfil.png";

        }else{

            $fotoPersona = $persona["foto"];

        }

?>

        <div class="usuario">

            <img
                src="<?php echo htmlspecialchars($fotoPersona); ?>"
                class="fotoUsuario"
                alt="Foto de perfil"
            >

            <h3>
                <?php echo htmlspecialchars($persona["usuario"]); ?>
            </h3>

        </div>

<?php

    }

}else{

    echo "<p>Aún no sigues a ningún usuario.</p>";

}
}elseif($seccion == "publicaciones"){

?>

    <h2>Publicaciones de <?php echo htmlspecialchars($usuario); ?></h2>

<?php

$sql = "SELECT contenido, imagen, fecha, logro
        FROM publicaciones
        WHERE id_usuario = ?
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();

$resultado = $stmt->get_result();


if($resultado && $resultado->num_rows > 0){

    while($publicacion = $resultado->fetch_assoc()){

?>

        <div class="publicacionPerfil">

<?php

        if(!empty($publicacion["contenido"])){

?>

            <p>
                <?php
                echo nl2br(
                    htmlspecialchars($publicacion["contenido"])
                );
                ?>
            </p>

<?php

        }


        if(!empty($publicacion["imagen"])){

?>

            <img
                src="<?php echo htmlspecialchars($publicacion["imagen"]); ?>"
                class="imagenPerfil"
                alt="Imagen de publicación"
            >

<?php

        }


        if($publicacion["logro"] == 1){

?>

            <div class="etiquetaLogro">
                🏆 Logro conseguido
            </div>

<?php

        }

?>

            <small>
                <?php
                echo htmlspecialchars($publicacion["fecha"]);
                ?>
            </small>

        </div>

<?php

    }

}else{

?>

    <p class="sinPublicaciones">
        Todavía no tienes publicaciones.
    </p>

<?php

}

}else{

?>

    <h2>Mi perfil</h2>

    <p>
        Selecciona Seguidores, Seguidos o Publicaciones.
    </p>

<?php

}

?>

        </div>

    </div>

</div>

<script src="daltonismo.js"></script>

</body>

</html>

<?php

$conn->close();

?>