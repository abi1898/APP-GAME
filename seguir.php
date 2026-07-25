<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("conexion.php");


if(!isset($_SESSION["id_usuario"])){

    die("No hay usuario iniciado.");

}


$idSeguidor = $_SESSION["id_usuario"];


if(!isset($_POST["id_usuario"])){

    die("No se recibió el usuario que quieres seguir.");

}


$idSeguido = (int)$_POST["id_usuario"];


if($idSeguidor == $idSeguido){

    die("No puedes seguirte a ti mismo.");

}


/* Comprobar si ya existe */

$sqlComprobar = "SELECT id
                 FROM seguidores
                 WHERE id_seguidor = '$idSeguidor'
                 AND id_seguido = '$idSeguido'";

$resultado = $conn->query($sqlComprobar);


if(!$resultado){

    die("Error al comprobar seguimiento: " . $conn->error);

}


/* Si todavía no lo sigue, guardar */

if($resultado->num_rows == 0){

    $sql = "INSERT INTO seguidores (id_seguidor, id_seguido)
            VALUES ('$idSeguidor', '$idSeguido')";


    if(!$conn->query($sql)){

        die("Error al guardar seguimiento: " . $conn->error);

    }

}


/* Regresar */

header("Location: solicitudes.php");
exit();

?>