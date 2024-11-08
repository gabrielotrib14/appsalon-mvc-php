<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controller\ApiController;
use Controller\CitaController;
use Controller\AdminController;
use Controller\LoginController;
use Controller\ServicioController;

$router = new Router();

// Iniciar Sesion
$router->get("/", [LoginController::class, "login"]);
$router->post("/", [LoginController::class, "login"]);

// Cerrar Sesion
$router->get("/logout", [LoginController::class, "logout"]);

// Crear cuenta
$router->get("/crear-cuenta", [LoginController::class, "crearCuenta"]);
$router->post("/crear-cuenta", [LoginController::class, "crearCuenta"]);

// Confirmar la cuenta 
$router->get("/confirmar-cuenta", [LoginController::class, "confirmarCuenta"]);
$router->get("/mensaje", [LoginController::class, "mensaje"]);


// Recuperar password
$router->get("/olvide", [LoginController::class, "olvide"]);
$router->post("/olvide", [LoginController::class, "olvide"]);
$router->get("/recuperar", [LoginController::class, "recuperar"]);
$router->post("/recuperar", [LoginController::class, "recuperar"]);

// Area privada
$router->get("/cita", [CitaController::class, "index"]);
$router->get("/admin", [AdminController::class, "admin"]);

// API
$router->get("/api/servicios", [ApiController::class, "index"]);
$router->post("/api/citas", [ApiController::class, "guardar"]);
$router->post("/api/eliminar", [ApiController::class, "eliminar"]);

// CRUD de servicios
$router->get("/servicios", [ServicioController::class, "index"]);
$router->get("/servicios/crear", [ServicioController::class, "crear"]);
$router->post("/servicios/crear", [ServicioController::class, "crear"]);
$router->get("/servicios/actualizar", [ServicioController::class, "actualizar"]);
$router->post("/servicios/actualizar", [ServicioController::class, "actualizar"]);
$router->post("/servicios/eliminar", [ServicioController::class, "eliminar"]);



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();