<?php

namespace Model;

use Model\ActiveRecord;

class Servicios extends ActiveRecord{
    // Base de datos
    protected static $tabla = "servicios";
    protected static $columnasDB = ["id", "nombre", "precio"];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->precio = $args["precio"] ?? "";

    }

    public function validarServicios(){
        if (!$this->nombre) {
            self::$alertas["error"][] = "El Nombre es obligatorio";
        }

        if (!$this->precio) {
            self::$alertas["error"][] = "El Precio es obligatorio";
        }

        if (!is_numeric($this->precio) && $this->precio) {
            self::$alertas["error"][] = "El Precio debe ser escrito en numeros";
        }

        return self::$alertas;
    }

}

?>