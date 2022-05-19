<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// Create and configure Slim app
/*$config = ['settings' => [
    'addContentLengthHeader' => false,
]];*/

require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new \Slim\App;

//rutas
require '../src/rutas/persona.php';
require '../src/rutas/empleado.php';


// Run app
$app->run();