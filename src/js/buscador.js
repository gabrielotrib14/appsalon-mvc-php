document.addEventListener("DOMContentLoaded", function (){
    iniciarApp();
})

function iniciarApp(){
    buscarPorFecha();
}

function buscarPorFecha(){
    let fecha = document.querySelector("#fecha")

    fecha.addEventListener("input", (e) => {
        const fechaSeleccionada = e.target.value;

        window.location = `?fecha=${fechaSeleccionada}`
    })
}