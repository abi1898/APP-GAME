let publicaciones = JSON.parse(localStorage.getItem("publicaciones")) || [];
let contenedor = document.getElementById("contenedorLogros");
contenedor.innerHTML = "";
let logros = publicaciones.filter(pub => pub.logro === true);
let html = "";
logros.forEach(pub => {
    html += `
        <div class="card">
            ${pub.imagen ? `<img src="${pub.imagen}">` : ""}
            <div class="descripcion">
                <h3>${pub.nombre}</h3>
                <p>${pub.texto}</p>
                <small>${pub.fecha}</small>
            </div>
        </div>
    `;
});