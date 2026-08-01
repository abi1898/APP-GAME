<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["id_usuario"])){
    die("No hay usuario iniciado.");
}

$idSeguidor = $_SESSION["id_usuario"];

if(!isset($_POST["id_usuario"])){
    die("No se recibió el usuario.");
}

$idSeguido = (int)$_POST["id_usuario"];

if($idSeguidor == $idSeguido){
    die("No puedes seguirte a ti mismo.");
}

$sqlComprobar = "SELECT id
                 FROM seguidores
                 WHERE id_seguidor = '$idSeguidor'
                 AND id_seguido = '$idSeguido'";

$resultado = $conn->query($sqlComprobar);

if(!$resultado){
    die("Error al comprobar seguimiento: " . $conn->error);
}

if($resultado->num_rows > 0){

    $sql = "DELETE FROM seguidores
            WHERE id_seguidor = '$idSeguidor'
            AND id_seguido = '$idSeguido'";

    if(!$conn->query($sql)){
        die("Error al dejar de seguir: " . $conn->error);
    }

}else{

    $sql = "INSERT INTO seguidores (id_seguidor, id_seguido)
            VALUES ('$idSeguidor', '$idSeguido')";

    if(!$conn->query($sql)){
        die("Error al seguir: " . $conn->error);
    }
}

$conn->close();

header("Location: solicitudes.php");
exit();

?>