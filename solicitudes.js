let usuarioActual = "user1";
let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
localStorage.setItem("usuarios", JSON.stringify(usuarios));
function seguir(id) {
    let usuarios = JSON.parse(localStorage.getItem("usuarios"));
    let yo = usuarios.find(u => u.id === usuarioActual);
    let otro = usuarios.find(u => u.id === id);
    if (!yo || !otro) return;
    if (yo.siguiendo.includes(id)) {
        yo.siguiendo = yo.siguiendo.filter(u => u !== id);
        otro.seguidores = otro.seguidores.filter(u => u !== usuarioActual);
    } else {
        yo.siguiendo.push(id);
        otro.seguidores.push(usuarioActual);
    }
    localStorage.setItem("usuarios", JSON.stringify(usuarios));
    mostrarUsuarios();
}
function mostrarUsuarios() {
    let usuarios = JSON.parse(localStorage.getItem("usuarios"));
    let contenedor = document.getElementById("contenedorUsuarios");
    contenedor.innerHTML = "";
    if (usuarios.length === 0) {
        contenedor.innerHTML = "<h3>No hay usuarios aún</h3>";
        return;
    }
    usuarios.forEach(u => {
        if (u.id === usuarioActual) return;
        let yo = usuarios.find(x => x.id === usuarioActual);
        let siguiendo = yo ? yo.siguiendo.includes(u.id) : false;
        contenedor.innerHTML += `
            <div class="usuario">
                <h3>${u.nombre}</h3>
                <p>Seguidores: ${u.seguidores.length}</p>
                <button onclick="seguir('${u.id}')">
                    ${siguiendo ? "Dejar de seguir" : "Seguir"}
                </button>
            </div>
        `;
    });
}