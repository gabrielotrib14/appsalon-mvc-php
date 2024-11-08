<?php
namespace Controller;

use MVC\Router;
use Model\AdminCita;

class AdminController{
    
    public static function admin(Router $router){

        session_start();
        isAdmin();

        $fecha = $_GET["fecha"] ?? date("Y-m-d");
        $fechas = explode("-", $fecha);

        if ( !checkdate($fechas[1], $fechas[2], $fechas[0]) ) {
            header("Location: /404");
        }

        // Consultar la base de datos
        $consulta = "select citas.id, citas.hora, concat( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= "usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio from citas
        left outer join usuarios
        on citas.usuarioId = usuarios.id
        left outer join citasServicios
        on citasServicios.citaId = citas.id
        left outer join servicios
        on servicios.id = citasServicios.servicioId
        where fecha = '${fecha}';";

        $citas = AdminCita::SQL($consulta);

        foreach ($citas as $cita) {
            $hora = $cita->hora;
            $hora_24 = explode(":", $hora);
            $hora_24 = $hora_24[0] . ":" .$hora_24[1];

           // h representa las horas en formato de 12 horas (con ceros iniciales).
           // i representa los minutos.
           // A añade AM o PM.

            $hora = date("h:i A", strtotime($hora_24));

            $cita->hora = $hora;

        }


        $router->render("admin/admin", [
            "nombre" => $_SESSION["nombre"],
            "citas" => $citas,
            "fecha" => $fecha
        ]);
    }
}