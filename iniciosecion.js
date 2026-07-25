const boton = document.getElementById('ingresar');
boton.addEventListener('click',()=>{
const usuario=document.querySelector('input[type="text]').value;
const contraseña=document.querySelector('input[type="password"]').value;
if(usuario==="" || contraseña===""){
alert("Completa todos los campos");
return;
}
alert("Bienvenido " + usuario);
window.location.href="menu.html";
});