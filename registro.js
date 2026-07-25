const registrar = document.getElementById("registrar");
registrar.addEventListener("click", function () {
    const usuario = document.querySelector('input[type="text"]').value;
    const contraseña = document.querySelector('input[type="password"]').value;
    if (usuario === "" || contraseña === "") {
        alert("Completa todos los campos.");
        return;
    }
    registrar.innerHTML = "REGISTRANDO...";
    registrar.style.background = "#007bc8";
    setTimeout(() => {
        alert("¡Registro exitoso!");
        window.location.href = "bienvenida.html";

    }, 1200);

});
function entrarInvitado(){

    let invitado={
        nombre:"Invitado",
        invitado:true
    };

    localStorage.setItem("usuario",JSON.stringify(invitado));

    window.location.href="menu.html";
}