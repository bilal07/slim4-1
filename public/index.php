<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';


$container = new Container();

$container->set('templating',function(){
    return new Mustache_Engine([
        'loader' => new Mustache_Loader_FilesystemLoader(
            __DIR__. '/../templates',
            ['extension' => ''] 
        )
    ]);
});

AppFactory::setContainer($container);

$app = AppFactory::create();


$app->get('/', '\App\Controller\FirstController:homepage');
$app->get('/hello', '\App\Controller\SecondController:hello');
$app->get('/default', '\App\Controller\SearchController:default');
$app->get('/search', '\App\Controller\SearchController:search');

/*$app->get('/', function(Request $request, Response $response){
    $response->getBody()->write('hello, world !');
    return $response;
});*/

$app->get('/hello/bilal', function(Request $request, Response $response){
    $response->getBody()->write('hello bilal');
    return $response;
});

$app->get('/hello/{name}', function(Request $request, Response $response, array $args = []){
    //$response->getBody()->write(sprintf("hello, %s", ucfirst($arg['name'])));
    $html = $this->get('templating')->render('hello.html', ['name' => ucfirst($args['name'])]);
    $response->getBody()->write($html);
    return $response;
});
$app->run();