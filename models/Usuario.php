<?php
namespace Model;

class Usuario extends ActiveRecord{
    // Base de datos - debe ser un espejo de la base de datos y las columnas en el mismo orden
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "apellido", "email", "telefono", "admin", "confirmado", "token", "password"];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;
    public $password;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->apellido = $args["apellido"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->telefono = $args["telefono"] ?? "";
        $this->admin = $args["admin"] ?? "0";
        $this->confirmado = $args["confirmado"] ?? "0";
        $this->token = $args["token"] ?? "";
        $this->password = $args["password"] ?? "";

    }

    // Mensajes de validacion para la creacion de una cuenta
    public function validarCrear() {
        if(!$this->nombre){
            self::$alertas["error"][] = "El Nombre es Obligatorio";
        }

        if(!$this->apellido){
            self::$alertas["error"][] = "El Apellido es Obligatorio";
        }

        if(!$this->telefono){
            self::$alertas["error"][] = "El Numero de telefono es Obligatorio";
        }

        if(!$this->email){
            self::$alertas["error"][] = "El Email es Obligatorio";
        }

        if(!$this->password){
            self::$alertas["error"][] = "El Password es Obligatorio";
        }

        if(strlen($this->password) < 6){
            self::$alertas["error"][] = "El Password debe Tener Minimo 6 Caracteres";
        }

        return self::$alertas;
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas["error"][] = "El Email es Obligatorio";
        }

        return self::$alertas;
    }

    public function validarNuevaPassword(){
        if (!$this->password) {
            self::$alertas["error"][] = "Introduce la nueva Password";
        }

        if (strlen($this->password) < 5 ) {
            self::$alertas["error"][] = "El Password debe ser de minimo 5 caracteres";
        }

        return self::$alertas;
    }

    // Revisa si el correo utilizado ya existe o esta asigando a alguna cuenta
    public function existeUsuario(){
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1" ; 

        $resultado = self::$db->query($query);
        
        if($resultado->num_rows) {
            self::$alertas["error"][] = "El usuario ya esta existe";
        } 

        return $resultado;

    }

    public function hashearPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function validarLogin() {
        if (!$this->email) {
            self::$alertas["error"][] = "El Email es Obligatorio";
        }

        if (!$this->password) {
            self::$alertas["error"][] = "El Password es Obligatorio";
        }

        return self::$alertas;
    }

    public function verificarConfirmadoAndPassword($auth, $usuario){

        $password = password_verify($auth, $usuario->password);

        if ($password & $usuario->confirmado) {
            return true;
            
        } else if (!$password || !$usuario->confirmado) {
            self::$alertas["error"][] = "El Password Ingresado es Incorrecto o la cuenta no esta confirmada";
        } 
        
    }


}

?>