<?php

$servidor = "sql203.byetcluster.com";
$usuario = "if0_42494568";
$password = "xaxn080219";
$basedatos = "if0_42494568_appgame";

$conn = new mysqli(
    $servidor,
    $usuario,
    $password,
    $basedatos
);

if($conn->connect_error){
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>