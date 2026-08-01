<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["usuario"]) || !isset($_SESSION["id_usuario"])){

    header("Location: iniciosecion.html");
    exit();

}

$idUsuario = $_SESSION["id_usuario"];

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nuevo_usuario"])){

    $nuevoUsuario = trim($_POST["nuevo_usuario"]);

    if($nuevoUsuario == ""){

        echo "<script>
        alert('Escribe un nombre de usuario.');
        window.location='perfil.php';
        </script>";

        exit();

    }

    if(strlen($nuevoUsuario) < 3){

        echo "<script>
        alert('El nombre debe tener al menos 3 caracteres.');
        window.location='perfil.php';
        </script>";

        exit();

    }

    $sqlExiste = "SELECT id
                  FROM usuarios
                  WHERE usuario = ?
                  AND id != ?";

    $stmtExiste = $conn->prepare($sqlExiste);

    $stmtExiste->bind_param(
        "si",
        $nuevoUsuario,
        $idUsuario
    );

    $stmtExiste->execute();

    $resultadoExiste = $stmtExiste->get_result();

    if($resultadoExiste->num_rows > 0){

        echo "<script>
        alert('Ese nombre de usuario ya está ocupado.');
        window.location='perfil.php';
        </script>";

        $stmtExiste->close();

        exit();

    }

    $stmtExiste->close();


    $sqlCambio = "UPDATE usuarios
                  SET usuario = ?
                  WHERE id = ?";

    $stmtCambio = $conn->prepare($sqlCambio);

    $stmtCambio->bind_param(
        "si",
        $nuevoUsuario,
        $idUsuario
    );

    if($stmtCambio->execute()){

        $_SESSION["usuario"] = $nuevoUsuario;

        echo "<script>
        alert('Nombre de usuario cambiado correctamente.');
        window.location='perfil.php';
        </script>";

        $stmtCambio->close();

        exit();

    }else{

        echo "<script>
        alert('No se pudo cambiar el nombre.');
        window.location='perfil.php';
        </script>";

        $stmtCambio->close();

        exit();

    }

}
if(isset($_GET["id"])){

    $idPerfil = (int)$_GET["id"];

}else{

    $idPerfil = $idUsuario;

}


$sqlPerfil = "SELECT id, usuario, foto
              FROM usuarios
              WHERE id = ?";

$stmtPerfil = $conn->prepare($sqlPerfil);

$stmtPerfil->bind_param(
    "i",
    $idPerfil
);

$stmtPerfil->execute();

$resultadoPerfil = $stmtPerfil->get_result();

$datosPerfil = $resultadoPerfil->fetch_assoc();


if(!$datosPerfil){

    header("Location: solicitudes.php");
    exit();

}


$usuarioPerfil = $datosPerfil["usuario"];


if(!empty($datosPerfil["foto"])){

    $foto = $datosPerfil["foto"];

}else{

    $foto = "fotos/perfil.png";

}


$esMiPerfil = ($idPerfil == $idUsuario);

$seccion = isset($_GET["seccion"])
    ? $_GET["seccion"]
    : "";

?>

<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Perfil | APP GAME</title>

<link rel="stylesheet" href="menu.css">

<link rel="stylesheet" href="perfil.css">

<link rel="stylesheet" href="daltonismo.css">

</head>

<body>

<div class="contenedor">


    <!-- BARRA LATERAL -->

    <div class="menu">

        <a href="perfil.php" class="icono">

            <img
                src="<?php echo htmlspecialchars($_SESSION["foto"] ?? "fotos/perfil.png"); ?>"
                alt="Perfil">

        </a>


        <a href="logros.php" class="icono">

            <img
                src="logrosimg.png"
                alt="Logros">

        </a>


        <a href="publicaciones.php" class="icono">

            <img
                src="publicacion.png"
                alt="Publicaciones">

        </a>


        <a href="solicitudes.php" class="icono">

            <img
                src="amigos.png"
                alt="Usuarios">

        </a>


        <a href="iniciosecion.html" class="icono">

            <img
                src="cerrar.png"
                alt="Cerrar sesión">

        </a>

    </div>


    <!-- PERFIL -->

    <div class="perfil">


        <img
            src="<?php echo htmlspecialchars($foto); ?>"
            alt="Foto de perfil"
            class="fotoPerfil"
        >


        <!-- NOMBRE Y BOTON EDITAR -->

        <div class="nombrePerfil">

            <h2>

                <?php

                echo htmlspecialchars(
                    $usuarioPerfil
                );

                ?>

            </h2>


            <?php if($esMiPerfil){ ?>

                <button
                    type="button"
                    class="editarNombre"
                    onclick="mostrarCambioNombre()"
                    title="Cambiar nombre">

                    ✏️

                </button>

            <?php } ?>

        </div>


        <!-- FORMULARIO PARA CAMBIAR NOMBRE -->

        <?php if($esMiPerfil){ ?>

        <form
            method="POST"
            class="formNombre"
            id="formNombre"
        >

            <input
                type="text"
                name="nuevo_usuario"
                placeholder="Nuevo nombre"
                maxlength="30"
                required
            >

            <button
                type="submit">

                Guardar

            </button>

        </form>

        <?php } ?>


        <!-- CAMBIAR FOTO -->

        <?php if($esMiPerfil){ ?>

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


            <label
                for="foto"
                class="boton">

                Seleccionar foto

            </label>


            <button
                type="submit"
                class="boton">

                Cambiar foto de perfil

            </button>

        </form>

        <?php } ?>


        <!-- SEGUIDORES / SEGUIDOS / PUBLICACIONES -->

        <div class="seguimiento">


            <a
                href="perfil.php?id=<?php echo $idPerfil; ?>&seccion=seguidores">

                <button
                    type="button"
                    class="boton">

                    Seguidores

                </button>

            </a>


            <a
                href="perfil.php?id=<?php echo $idPerfil; ?>&seccion=seguidos">

                <button
                    type="button"
                    class="boton">

                    Seguidos

                </button>

            </a>


            <a
                href="perfil.php?id=<?php echo $idPerfil; ?>&seccion=publicaciones">

                <button
                    type="button"
                    class="boton">

                    Publicaciones

                </button>

            </a>

        </div>


        <div class="publicaciones">


<?php
if($seccion == "seguidores"){

?>

    <h2>

        Seguidores de

        <?php

        echo htmlspecialchars(
            $usuarioPerfil
        );

        ?>

    </h2>

<?php

$sql = "SELECT
            usuarios.id,
            usuarios.usuario,
            usuarios.foto

        FROM seguidores

        INNER JOIN usuarios

        ON seguidores.id_seguidor = usuarios.id

        WHERE seguidores.id_seguido = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $idPerfil
);

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

            <a
                href="perfil.php?id=<?php echo $persona["id"]; ?>"
                class="enlacePerfil"
            >

                <img
                    src="<?php echo htmlspecialchars($fotoPersona); ?>"
                    class="fotoUsuario"
                    alt="Foto de perfil"
                >

                <h3>

                    <?php

                    echo htmlspecialchars(
                        $persona["usuario"]
                    );

                    ?>

                </h3>

            </a>

        </div>

<?php

    }

}else{

?>

    <p>

        Aún no tiene seguidores.

    </p>

<?php

}
}elseif($seccion == "seguidos"){

?>

    <h2>

        <?php

        echo htmlspecialchars(
            $usuarioPerfil
        );

        ?>

        sigue a:

    </h2>

<?php

$sql = "SELECT
            usuarios.id,
            usuarios.usuario,
            usuarios.foto

        FROM seguidores

        INNER JOIN usuarios

        ON seguidores.id_seguido = usuarios.id

        WHERE seguidores.id_seguidor = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $idPerfil
);

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

            <a
                href="perfil.php?id=<?php echo $persona["id"]; ?>"
                class="enlacePerfil"
            >

                <img
                    src="<?php echo htmlspecialchars($fotoPersona); ?>"
                    class="fotoUsuario"
                    alt="Foto de perfil"
                >

                <h3>

                    <?php

                    echo htmlspecialchars(
                        $persona["usuario"]
                    );

                    ?>

                </h3>

            </a>

        </div>

<?php

    }

}else{

?>

    <p>

        Aún no sigue a ningún usuario.

    </p>

<?php

}

}elseif($seccion == "publicaciones"){

?>

    <h2>

        Publicaciones de

        <?php

        echo htmlspecialchars(
            $usuarioPerfil
        );

        ?>

    </h2>

<?php

$sql = "SELECT
            contenido,
            imagen,
            fecha,
            logro

        FROM publicaciones

        WHERE id_usuario = ?

        ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $idPerfil
);

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
                    htmlspecialchars(
                        $publicacion["contenido"]
                    )
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

        <?php

        if($esMiPerfil){

            echo "Todavía no tienes publicaciones.";

        }else{

            echo "Este usuario todavía no tiene publicaciones.";

        }

        ?>

    </p>

<?php

}

}else{

?>

    <h2>

<?php

if($esMiPerfil){

    echo "Mi perfil";

}else{

    echo "Perfil de " .
         htmlspecialchars(
             $usuarioPerfil
         );

}

?>

    </h2>

    <p>

<?php

if($esMiPerfil){

    echo "Selecciona Seguidores, Seguidos o Publicaciones.";

}else{

    echo "Selecciona una sección para ver el perfil.";

}

?>

    </p>

<?php

}

?>

        </div>

    </div>

</div>


<script src="daltonismo.js"></script>


<script>

function mostrarCambioNombre(){

    const formulario =
        document.getElementById("formNombre");

    if(formulario.style.display === "flex"){

        formulario.style.display = "none";

    }else{

        formulario.style.display = "flex";

    }

}

</script>


</body>

</html>

<?php

$stmtPerfil->close();

$conn->close();

?>