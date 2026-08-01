<?php
session_start();
include("conexion.php");

$maxIntentos = 5;
$tiempoBloqueo = 300;

if(isset($_SESSION["bloqueado_hasta"]) && time() < $_SESSION["bloqueado_hasta"]){
    $restante = $_SESSION["bloqueado_hasta"] - time();
    $minutos = ceil($restante / 60);

    echo "<script>
    alert('Has superado el número de intentos. Intenta nuevamente en aproximadamente $minutos minutos.');
    window.location='iniciosecion.html';
    </script>";

    exit();
}

if(isset($_SESSION["bloqueado_hasta"]) && time() >= $_SESSION["bloqueado_hasta"]){
    unset($_SESSION["bloqueado_hasta"]);
    $_SESSION["intentos"] = 0;
}

if(!isset($_SESSION["intentos"])){
    $_SESSION["intentos"] = 0;
}

$usuario = $_POST["usuario"];
$password = $_POST["password"];

$sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
$resultado = $conn->query($sql);

if($resultado && $resultado->num_rows == 1){

    $fila = $resultado->fetch_assoc();

    if(password_verify($password, $fila["password"])){

        $_SESSION["usuario"] = $fila["usuario"];
        $_SESSION["id_usuario"] = $fila["id"];
        $_SESSION["foto"] = $fila["foto"];

        $_SESSION["intentos"] = 0;
        unset($_SESSION["bloqueado_hasta"]);

        header("Location: perfil.php");
        exit();

    }else{

        $_SESSION["intentos"]++;

    }

}else{

    $_SESSION["intentos"]++;

}

if($_SESSION["intentos"] >= $maxIntentos){

    $_SESSION["bloqueado_hasta"] = time() + $tiempoBloqueo;

    echo "<script>
    alert('Has superado los 5 intentos. Tu inicio de sesión está bloqueado durante 3 minutos.');
    window.location='iniciosecion.html';
    </script>";

}else{

    $restantes = $maxIntentos - $_SESSION["intentos"];

    echo "<script>
    alert('Usuario o contraseña incorrectos. Te quedan $restantes intentos.');
    window.location='iniciosecion.html';
    </script>";
}

$conn->close();
?>