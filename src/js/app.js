let paso = 1;
let pasoInicial = 1;
let pasoFinal = 3;

const diccionarioHora = {
    12: 12,
    13: 1,
    14: 2,
    15: 3,
    16: 4,
    17: 5,
    18: 6,
    19: 7,
    20: 8,
    21: 9,
    22: 10,
    23: 11,
    24: 12
}

const cita = {
    id : "",
    nombre : "",
    fecha : "",
    hora : "",
    servicios : []
}

function app() {
    mostrarSeccion(); // Muestra la seccion por defecto que es la numero 1
    tab(); // Cambiar la seccion cuando se presione los tabs
    botonesPaginacion(); // Se usa para saber que botones mostrar en cada seccion
    paginaSiguiente();
    paginaAnterior();

    
    consultarApi(); // Consulta la Api en el backend de PHP
    idCliente(); // Obtener el id del cliente e introducirlo dentro del objeto de cita
    nombreCliente(); // Obtener el nombre del cliente
    fechaCita() // Obtener la fecha de la cita
    horaCita() // Obtener la hora de la cita
    mostrarResumen() // Mostramos el resumen con todos los datos del usuario
}

function tab(){
    let tabs = document.querySelectorAll(".tabs button")

    for (let i = 0; i < tabs.length; i++) {

        tabs[i].addEventListener("click", (e) => {

            paso = parseInt(e.target.dataset.paso); // dataset se usa para acceder a los atributos personalizados

            mostrarSeccion();
            botonesPaginacion();
        })

    }

}

function mostrarSeccion(){
    // Seleccionar la seccion son el paso
    const seccion = document.querySelector(`#paso-${paso}`);

    // Ocultar la seccion que tenga la clase de mostrar
    let ocultar = document.querySelector(".mostrar")
    ocultar ? ocultar.classList.remove("mostrar") : false;

    // Ocultar la seccion que tenga la clase de actual
    let actual = document.querySelector(".actual")
    actual ? actual.classList.remove("actual") : false;

    // Mostrar la seccion a la que se le dio click
    seccion.classList.add("mostrar");

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab.classList.add("actual");

    const seccionResumen = document.querySelector(`[data-paso="3"]`)

    if(seccionResumen.classList.contains("actual")){
        mostrarResumen()
    }
}

function botonesPaginacion(){
    const anterior = document.querySelector('#anterior');
    const siguiente = document.querySelector("#siguiente");

    if (paso === 1) {

        anterior.classList.add("ocultar");
        siguiente.classList.remove("ocultar");

    } else if (paso ===  3) {

        anterior.classList.remove("ocultar");
        siguiente.classList.add("ocultar");

    } else {

        anterior.classList.remove("ocultar");
        siguiente.classList.remove("ocultar");

    }
}

function paginaAnterior(){
    const anterior = document.querySelector("#anterior");

    anterior.addEventListener("click", () => {

        if (paso <= pasoInicial) {

            return
        }
        paso--

        botonesPaginacion()
        mostrarSeccion()

    })
}


function paginaSiguiente(){
    const siguiente = document.querySelector("#siguiente");

    siguiente.addEventListener("click", () => {
        if (paso >= pasoFinal) {

            return
        }
        paso++

        botonesPaginacion()
        mostrarSeccion()

    })
}

async function consultarApi(){

    try {
        // location.origin trae la url base de tu proyecto
        const url = `/api/servicios`

        const resultado = await fetch(url);
        const servicios = await resultado.json()

        mostrarServicios(servicios)

    } catch (error) {
        console.log(error)
    }

}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement("P")
        nombreServicio.classList.add("nombre-servicio");
        nombreServicio.textContent = nombre

        const precioServicio = document.createElement("P")
        precioServicio.classList.add("precio-servicio");
        precioServicio.textContent = precio + "$"

        const servicioDiv = document.createElement("DIV")
        servicioDiv.classList.add("servicio")
        servicioDiv.dataset.idServicio = id;
        servicioDiv.appendChild(nombreServicio)
        servicioDiv.appendChild(precioServicio)

        //Creamos un evento para que se active al hacer click lo hacemos mediante un callback para que solo envie el servicio al que le dimos click
        servicioDiv.onclick = function (){
            seleccionarServicio(servicio)
        }

        document.querySelector(".listado-servicios").appendChild(servicioDiv);

    });
}

function seleccionarServicio(servicio){
    const { id } = servicio;
    const { servicios } = cita;

    let divServicio = document.querySelector(`[data-id-servicio="${id}"]`)

    // Comprobar si un servicio ya fue agregado
    if (servicios.some(e => e.id === id)) {

        // Eliminarlo
        cita.servicios = servicios.filter(e => e.id !== id )

        divServicio.classList.remove("seleccionado");

        // Otro metodo para eliminarlo
        //const index = servicios.findIndex(e => e.id === id);
        //if (index !== -1) {
        //servicios.splice(index, 1);
        //}

    } else {
        // Agregarlo

        cita.servicios = [...servicios, servicio]

        divServicio.classList.add("seleccionado");

    }
    
}

function nombreCliente(){
    const nombreCliente = document.querySelector('#nombre').value

    cita.nombre = nombreCliente

}

function fechaCita(){
    const inputFecha = document.querySelector("#fecha")

    inputFecha.addEventListener("input", e => {
        const dia = new Date(e.target.value)

        let fecha = dia.getUTCDay()

        if(fecha === 0 || fecha === 6){
            e.target.value = ""

                mostrarAlerta("Fines de semana no permitidos", "error", "form")


        } else {
            cita.fecha = e.target.value
            
            const formulario = document.querySelector("form")

            const alerta = document.querySelector(".alertas")

        }
    })

}

function horaCita(){
    const inputHora = document.querySelector("#hora")
    

    inputHora.addEventListener("input", e => {
        let horaCita = e.target.value

        let hora = horaCita.split(":")

        if(hora[0] < 9 || hora[0] > 16){

            mostrarAlerta("Horario disponible de 9:00AM a 4:00PM", "error", "form")

            e.target.value = ""
        } else {
            const formulario = document.querySelector("form")
            const alerta = document.querySelector("form .alertas")

            if (alerta) formulario.removeChild(alerta)

            cita.hora = e.target.value
        }

    })
}

function idCliente(){
    const id = document.querySelector("input[type='hidden']").value

    cita.id = id;

}

function mostrarResumen(){
    const resumen = document.querySelector(".contenido-resumen")
    const {nombre, fecha, hora, servicios} = cita
    let precioTotal = 0;

    // Limpiar el div de resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild)
    }

    if (Object.values(cita).includes("") || cita.servicios.length === 0) {

        mostrarAlerta("Falta información por completar", "error", ".contenido-resumen")

        return
    } 

    //Creamos un heading para resumen
    const headingResumen = document.createElement('H3')
    headingResumen.textContent = "Resumen de Servicios"
    resumen.appendChild(headingResumen)

    servicios.forEach(servicio => {
        const {nombre, precio} = servicio
        const contenedorServicio = document.createElement("DIV")

        contenedorServicio.classList.add("contenedor-servicios")
        
        const textoServicio = document.createElement("P")
        textoServicio.textContent = nombre

        const precioServicio = document.createElement("P")
        precioServicio.innerHTML = `<span>Precio:</span> ${precio}$`

        contenedorServicio.appendChild(textoServicio)
        contenedorServicio.appendChild(precioServicio)

        resumen.appendChild(contenedorServicio)

        precioTotal += parseInt(precio)

    })

    // Creamos un precio total
    const precioTotalServicios = document.createElement("P")

    precioTotalServicios.innerHTML = `<span>Total:</span> ${precioTotal}$`
    precioTotalServicios.classList.add("precio-total")
    resumen.appendChild(precioTotalServicios)

    //Creamos un heading para informacion cita
    const headingInformacion = document.createElement('H3')
    headingInformacion.textContent = "Información Cita"
    headingInformacion.classList.add("heading-informacion")
    resumen.appendChild(headingInformacion)

    // Formatear el div de resumen

    const nombreCliente = document.createElement("P")
    nombreCliente.innerHTML = `<span> Nombre:</span> ${nombre}`

    // Formatear la fecha
    const date = `${fecha}T${hora}:00`;
    const fechaHora = new Date(date)

    // const mes = fechaHora.getMonth() 
    // const dia = fechaHora.getDate() + 1
    // const year = fechaHora.getFullYear()

    // const fechaUtc = new Date( Date.UTC(year, mes, dia) )

    const opciones = { weekday: "long", year: "numeric", month: "long", day: "numeric"}
    const fechaFormateada = fechaHora.toLocaleDateString("es-MX", opciones)

    const fechaCita = document.createElement("P")
    fechaCita.innerHTML = `<span> Fecha:</span> ${fechaFormateada}`

    resumen.appendChild(nombreCliente)
    resumen.appendChild(fechaCita)

    const options = { hour: '2-digit', minute: '2-digit', hour12: true };
    const timeString = fechaHora.toLocaleTimeString(undefined, options);

    const horaCita = document.createElement("P")
    horaCita.innerHTML = `<span> Hora:</span> ${timeString}`

    resumen.appendChild(horaCita)

    // if (horaArray[0] < 12) {
    //     const horaCita = document.createElement("P")
    //     horaCita.innerHTML = `<span> Hora:</span> ${horaArray[0]}:${horaArray[1]} AM`
    //     resumen.appendChild(horaCita)



    // } else {
    //     const horaCita = document.createElement("P")
    //     horaCita.innerHTML = `<span> Hora:</span> ${diccionarioHora[horaArray[0]]}:${horaArray[1]} PM`
    //     resumen.appendChild(horaCita)
    // }

    // Boton para crear una cita
    const botonReservar = document.createElement("BUTTON")
    botonReservar.classList.add("boton")
    botonReservar.classList.add("boton-reservar")
    botonReservar.textContent = "Reservar cita"

    botonReservar.onclick = reservarCita;
    resumen.appendChild(botonReservar)

}

async function reservarCita() {

    const {id, fecha, hora, servicios} = cita
    const idServicio = servicios.map(servicio => servicio.id)

    // El form data va a actuar como el submit pero con javascript
    const datos = new FormData();

    // Introducir datos en el form data
    
    datos.append("fecha", fecha)
    datos.append("hora", hora)
    datos.append("usuarioId", id)
    datos.append("servicios", idServicio)

    try {
        // Peticion hacias la API
        const url = "/api/citas"

        const respuesta = await fetch(url, {
            method : "POST",
            body : datos
        })

        const resultado = await respuesta.json()

        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita Creada",
                text: "Se ha agendado la cita correctamente.",
                button: "OK"
            }).then(() => {
                window.location.reload()
            })

        }

    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ha ocurrido un error"

          });
    }



    // Tip para poder ver los datos que vamos a enviar tomando una copia del formData y lo va formatear dentro de este console.log

    //console.log([...datos])
}

function mostrarAlerta(mensaje, tipo, contenedor){
    let alertaPrevia = document.querySelector(".alertas")

    if (alertaPrevia) {
        alertaPrevia.remove()
    }


    const alerta = document.createElement("DIV")
    alerta.textContent = mensaje
    alerta.classList.add("alertas")
    alerta.classList.add(tipo)

    const elemento = document.querySelector(contenedor)
    elemento.appendChild(alerta)

}

document.addEventListener("DOMContentLoaded", app);