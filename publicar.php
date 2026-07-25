<?php

session_start();

include("conexion.php");

if(!isset($_SESSION["id_usuario"])){

    header("Location: iniciosecion.html");
    exit();

}

$idUsuario = $_SESSION["id_usuario"];

$contenido = trim($_POST["contenido"]);

$imagen = NULL;

$logro = isset($_POST["logro"]) ? 1 : 0;

if($contenido == "" && empty($_FILES["imagen"]["name"])){

    header("Location: publicaciones.php");
    exit();

}
if(isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == 0){

    $nombreOriginal = $_FILES["imagen"]["name"];

    $temporal = $_FILES["imagen"]["tmp_name"];

    $extension = pathinfo(
        $nombreOriginal,
        PATHINFO_EXTENSION
    );

    $nombreNuevo = time() . "_" . uniqid() . "." . $extension;

    $ruta = "publicaciones/" . $nombreNuevo;


    if(move_uploaded_file($temporal, $ruta)){

        $imagen = $ruta;

    }

}

$sql = "INSERT INTO publicaciones
        (id_usuario, contenido, imagen, logro)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "issi",
    $idUsuario,
    $contenido,
    $imagen,
    $logro
);


if($stmt->execute()){

    header("Location: publicaciones.php");
    exit();

}else{

    echo "Error al publicar: " . $conn->error;

}

?>