const usuario = JSON.parse(localStorage.getItem("usuario"));

if(usuario){

    document.getElementById("nombreUsuario").innerHTML=usuario.nombre;
    document.getElementById("datosUsuario").innerHTML=usuario.correo;
}else{
    document.getElementById("nombreUsuario").innerHTML="Invitado";
    document.getElementById("datosUsuario").innerHTML="Regístrate para comenzar";
}
if(localStorage.getItem("daltonico")=="true"){
    document.body.classList.add("daltonico");

}