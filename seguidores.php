<?php

session_start();

include("conexion.php");


if(!isset($_SESSION["id_usuario"])){

    header("Location: iniciosecion.html");
    exit();

}


$idSeguidor = $_SESSION["id_usuario"];


if(!isset($_POST["id_usuario"])){

    header("Location: solicitudes.php");
    exit();

}


$idSeguido = (int)$_POST["id_usuario"];
if($idSeguidor == $idSeguido){

    header("Location: solicitudes.php");
    exit();

}
$comprobar = "SELECT id
              FROM seguidores
              WHERE id_seguidor = '$idSeguidor'
              AND id_seguido = '$idSeguido'";


$resultado = $conn->query($comprobar);


if($resultado->num_rows == 0){

    $sql = "INSERT INTO seguidores(id_seguidor, id_seguido)
            VALUES('$idSeguidor', '$idSeguido')";

    $conn->query($sql);

}


header("Location: solicitudes.php");

exit();

?>