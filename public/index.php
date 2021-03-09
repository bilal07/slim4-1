<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->get('/', function(Request $request, Response $response){
    $response->getBody()->write('hello, world !');
    return $response;
});

$app->get('/hello/bilal', function(Request $request, Response $response){
    $response->getBody()->write('hello bilal');
    return $response;
});

$app->get('/hello/{name}', function(Request $request, Response $response, array $arg = []){
    $response->getBody()->write(sprintf("hello, %s", ucfirst($arg['name'])));
    return $response;
});
$app->run();