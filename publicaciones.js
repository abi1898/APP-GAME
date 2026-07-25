let nombre = localStorage.getItem("nombreUsuario");
if (nombre == null || nombre == "") {
    nombre = "Jugador";
document.getElementById("nombreUsuario").innerHTML = nombre;
let publicaciones = JSON.parse(localStorage.getItem("publicaciones")) || [];
mostrarPublicaciones();
function publicar() {
    let texto = document.getElementById("texto").value;
    let archivo = document.getElementById("imagen").files[0];
    let esLogro = document.getElementById("esLogro").checked;
    if (texto == "" && !archivo) {
        alert("Escribe algo o selecciona una imagen.");
        return;
    }
    let fecha = new Date();
    let hora =
        fecha.getDate() + "/" +
        (fecha.getMonth() + 1) + "/" +
        fecha.getFullYear() + " - " +
        fecha.getHours() + ":" +
        ("0" + fecha.getMinutes()).slice(-2);
    if (archivo) {
        let lector = new FileReader();
        lector.onload = function(e) {
     publicaciones.unshift({ 
    nombre: nombre,
    texto: texto,
    imagen: e.target.result,
    fecha: hora,
    logro: esLogro,
    likes: 0,
    comentarios: []

});
        }
        lector.readAsDataURL(archivo);
    } else {
       publicaciones.unshift({
    nombre: nombre,
    texto: texto,
    imagen: "",
    fecha: hora,
    logro: esLogro,
    likes: 0,
    comentarios: []

});
    guardar();

    }
}
function guardar() {
  localStorage.setItem(
        "publicaciones",
        JSON.stringify(publicaciones)
    );
    document.getElementById("texto").value = "";
    document.getElementById("imagen").value = "";
    document.getElementById("esLogro").checked = false;
    mostrarPublicaciones();
}
function mostrarPublicaciones() {
    let muro = document.getElementById("muro");
    muro.innerHTML = "";
 publicaciones.forEach((pub, i) => {
        muro.innerHTML += `
<div class="publicacion"
<div class="encabezado">
<img src="perfil.png">
<div>
<h3>${pub.nombre}</h3>
<small>${pub.fecha}</small>
</div>
</div>
<p>${pub.texto}</p>
${pub.imagen != "" ? `<img class="foto" src="${pub.imagen}">` : ""}
<div class="acciones">
<button onclick="like(${i})">
❤️ ${pub.likes}
</button>
<button onclick="comentar(${i})">
💬 Comentar
</button>
<button onclick="eliminar(${i})">
🗑 Eliminar
</button>
</div>
</div>
`;
    });
}
function like(i){
    publicaciones[i].likes++;
    guardar();
}
function comentar(i){
    let comentario = prompt("Escribe un comentario");
    if(comentario!=null && comentario!=""){
        publicaciones[i].comentarios.push(comentario);
    alert("Comentario agregado.");
        guardar();
  }
}
function eliminar(i){
    if(confirm("¿Eliminar publicación?")){
        publicaciones.splice(i,1);
        guardar();
    }
}
}