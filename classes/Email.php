<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){

        // Crear el objeto de email

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV["EMAIL_PORT"];
        $mail->Username = $_ENV["EMAIL_USER"];
        $mail->Password = $_ENV["EMAIL_PASSWORD"];

        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("gabrielotrib14@gmail.com", "Admin Appsalon");
        $mail->Subject = "Confirmacion de correo electronico";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p> <strong>Hola " . $this->nombre . " has creado tu cuenta en Appsalon, para poder acceder por favor confirma tu cuenta haciendo click en el siguiente enlace</strong></p>";
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV["APP_URL"] . "/confirmar-cuenta?token=" . $this->token . "'>Enlace de verificacion.</a></p>";
        $contenido .= "Si tu no solicitaste esta cuenta, por favor ignora este mensaje.";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }

    public function enviarRecuperacion(){
        // Crear el objeto de email

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV["EMAIL_PORT"];
        $mail->Username = $_ENV["EMAIL_USER"];
        $mail->Password = $_ENV["EMAIL_PASSWORD"];

        $mail->setFrom("cuentas@appsalon.com");
        $mail->addAddress("gabrielotrib14@gmail.com", "Admin Appsalon");
        $mail->Subject = "Recuperacion de Password";

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p> <strong>Hola " . $this->nombre . " hemos recibido una solicitud de cambio de Password</strong></p>";
        $contenido .= "<p>Presiona aqui: <a href='" . $_ENV["APP_URL"] . "/recuperar?token=" . $this->token . "'>Enlace de recuperacion.</a></p>";
        $contenido .= "Si no hiciste esta solicitud, por favor ignora este mensaje.";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el email
        $mail->send();
    }
}

?>