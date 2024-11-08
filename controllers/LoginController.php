<?php
namespace Controller;

use MVC\Router;
use Model\Usuario;
use Classes\Email;
use Model\ActiveRecord;

class LoginController{

    public static function login(Router $router) {
        $alertas = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $credenciales = $_POST;

            $auth = new Usuario($credenciales);

            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = $auth->where("email", $auth->email);

                if($usuario){
                    // Verificar el password y si el usuario verifico su correo
                    if($auth->verificarConfirmadoAndPassword($auth->password, $usuario)){
                        // Autenticar al usuario
                        session_start();

                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;

                        // Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION["admin"] = $usuario->admin ?? null;

                            header("Location: /admin");
                        } else {
                            header("Location: /cita");
                        }


                    }
                    
                } else {
                    Usuario::setAlerta("error", "Usuario no encontrado");
                }

                

            }

        };

        $alertas = Usuario::getAlertas();

        $router->render("auth/login", [
            "alertas" => $alertas
        ]);
    }

    public static function logout() {
        session_start();

        $_SESSION = [];

        header("Location: /");
        
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $auth = new Usuario($_POST);

            // Verificar que el usuario escriba un email
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                // Verificar que el usuario exista y esta confimardo
                $usuario = Usuario::where("email", $auth->email);


                if ($usuario && $usuario->confirmado === "1") {
                    // Generamos un nuevo token para que el usuario pueda cambiar la clave
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviamos el enlace donde se hara el cambio de password
                    Usuario::setAlerta("exito", "Revisa tu correo");

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarRecuperacion();
                    
                } else {
                    Usuario::setAlerta("error", "El usuario no existe o aun no ha sido confirmado");
                }

            }   

            $alertas = Usuario::getAlertas();

        }

        

        $router->render("auth/olvide", [
            "alertas" => $alertas
        ]);
    }

    public static function recuperar(Router $router) {
        $alertas = [];
        $token = s($_GET["token"]);
        $error = false;
        
        $usuario = Usuario::where("token", $token);
                
        if (!$usuario) {
            Usuario::setAlerta("error", "Token no valido");
            $error = true;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $password = new Usuario($_POST);

            // Validar que el campo no este vacio y que el Password tenga mas de 5 caracteres
            $alertas = $password->validarNuevaPassword();

            if (empty($alertas)) {
                $usuario->password = null;
                $password->hashearPassword();

                $usuario->password = $password->password;

                $usuario->token = "";

                $resultado = $usuario->guardar();

                if ($resultado) {
                    header("Location: /");
                }

            }

        }
        
        $alertas = Usuario::getAlertas();


        $router->render("auth/recuperar", [
            "alertas" => $alertas,
            "error" => $error
        ]);
    }

    public static function crearCuenta(Router $router) {
        $usuario = new Usuario;

        // Alertas vacias 
        $alertas = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
     
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarCrear();

            // Revisar que alertas este vacio
            if (empty($alertas)) {
                // Verificar si el correo electronico aun no se haya utilizado
                $existeUsuario = $usuario->existeUsuario();

                if ($existeUsuario->num_rows) {
                    $alertas = Usuario::getAlertas(); 
                } else {
                    // Hashear el password
                    $usuario->hashearPassword();

                    // Generar un token unico
                    $usuario->crearToken();

                    // Enviar email de comprobacion
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    // Crar el usario en la base de datos
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header("Location: /mensaje");
                    }

                }
            }      
        }
        


        $router->render("auth/crear-cuenta", [
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render("auth/mensaje");
    }

    public static function confirmarCuenta(Router $router){
        $alertas = [];

        $token = s($_GET["token"]);

        $usuario = Usuario::where("token", $token);

        if (!empty($usuario)) {
            // Modificar a usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();

            Usuario::setAlerta("exito", "La cuenta ha sido verificada correctamente");

        } else {
            // Mostrar mensaje de error
            Usuario::setAlerta("error", "Token no valido");
           
        }

        // Obtener alertas 
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render("auth/confirmar-cuenta", [
            "alertas" => $alertas
        ]);
    }
}




?>