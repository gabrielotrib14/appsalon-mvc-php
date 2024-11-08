<?php
namespace Controller;

use Model\Servicios;
use Model\Cita;
use Model\CitaServicio;

class ApiController{
    
    public static function index(){
        $servicios = Servicios::all();

        echo json_encode($servicios);

    }

    public static function guardar(){


        // Almacena la cita y devuelve el id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado["id"];

        // Almacena la cita y el servicio en la tabla citaServicio
        
        $idServicios = explode(",", $_POST['servicios']);


        foreach($idServicios as $idServicio) {
            $args = [
                "citaId" => $id,
                "servicioId" => $idServicio
            ];

            $citaServicio = new CitaServicio($args);

            $citaServicio->guardar();

        }


        echo json_encode(["resultado" => $resultado]);
    }

    public static function eliminar(){
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST["id"];

            $cita = Cita::find($id);
            $cita->eliminar();

            header("Location:" . " " . $_SERVER["HTTP_REFERER"]);
            
        }

    }
}


?>