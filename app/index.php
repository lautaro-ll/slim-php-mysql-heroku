<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';

require_once './controllers/UsuarioController.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

$mw = function ($request, $response, $next) {
  $response->getBody()->write('BEFORE');
  $response = $next($request, $response);
  $response->getBody()->write('AFTER');

  return $response;
};

$VerificarUserYPass = function ($request, $response, $next) {
 
    $response->getBody()->write("Entré al MW!");
    /*$ArrayDeParametros = $request->getParsedBody();
    var_dump($ArrayDeParametros);
    $usuario=$ArrayDeParametros['usuario'];
    $clave=$ArrayDeParametros['clave'];

    if($usuario=="administrador" && $clave=="1234")
    {
      $response->getBody()->write("<h3>Bienvenido $usuario </h3>");*/
      $response = $next($request, $response);
    //}
  return $response;  
};

// Routes
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    //modificar
    //borrar
  });

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Slim Framework 4 PHP");
    return $response;

})->add($mw);

$app->run();
