<?php
session_start();
include("conexion.php");
if(!isset($_SESSION["id_usuario"])){
    header("Location: iniciosecion.html");
    exit();
}
$id = $_SESSION["id_usuario"];
if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0){
    $nombreFoto = time() . "_" . $_FILES["foto"]["name"];
    $temporal = $_FILES["foto"]["tmp_name"];
    $ruta = "fotos/" . $nombreFoto;
    if(move_uploaded_file($temporal, $ruta)){
        $sql = "UPDATE usuarios 
                SET foto='$ruta'
                WHERE id='$id'";
        if($conn->query($sql)){
            $_SESSION["foto"] = $ruta;
            header("Location: perfil.php");
            exit();
        }else{
            echo "Error SQL: " . $conn->error;

        }
    }else{
        echo "No se pudo subir la imagen";
    }
}else{
    echo "No seleccionaste ninguna imagen";

}
$conn->close();
?>