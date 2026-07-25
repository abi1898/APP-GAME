<?php
session_start();
include("conexion.php");
$usuario = $_POST["usuario"];
$password = $_POST["password"];
$sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
$resultado = $conn->query($sql);
if($resultado->num_rows == 1){
    $fila = $resultado->fetch_assoc();
    if(password_verify($password, $fila["password"])){
      $_SESSION["usuario"] = $fila["usuario"];
$_SESSION["id_usuario"] = $fila["id"];
$_SESSION["foto"] = $fila["foto"];
        header("Location: perfil.php");
        exit();
    } else {
        echo "<script>
        alert('Usuario o contraseña incorrectos');
        window.location='iniciosecion.html';
        </script>";
    }
}else{
    echo "<script>
    alert('Usuario o contraseña incorrectos');
    window.location='iniciosecion.html';
    </script>";
}
$conn->close();
?>