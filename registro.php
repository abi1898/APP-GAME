<?php
include("conexion.php");
$usuario = $_POST["usuario"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);$consulta = "SELECT * FROM usuarios WHERE usuario='$usuario'";
$resultado = $conn->query($consulta);
if($resultado->num_rows > 0){
    echo "<script>
    alert('Ese nombre de usuario ya existe');
    window.location='bienvenida.html';
    </script>";
}else{
$sql = "INSERT INTO usuarios(usuario,password,foto)
        VALUES('$usuario','$password','fotos/perfil.png')";
    if($conn->query($sql)){
        echo "<script>
        window.location='bienvenida.html';
        </script>";
    }else{
        echo "Error al registrar.";
    }
}
$conn->close();

?>