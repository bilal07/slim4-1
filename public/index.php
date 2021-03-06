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

$container->set('session', function(){
    return new \SlimSession\Helper();
});

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->add(new \Slim\Middleware\Session);

$app->get('/', '\App\Controller\FirstController:homepage');
$app->get('/hello', '\App\Controller\SecondController:hello');
$app->get('/default', '\App\Controller\SearchController:default');
$app->get('/search', '\App\Controller\SearchController:search');
$app->any('/form', '\App\Controller\SearchController:form');
$app->get('/api', '\App\Controller\ApiController:search');
$app->get('/defshop', '\App\Controller\ShopController:defShop');
$app->get('/details/{id:[0-9]+}', '\App\Controller\ShopController:details');
$app->any('/login', '\App\Controller\AuthController:login');
$app->get('/secure', '\App\Controller\SecureController:default')->add(new \App\Middleware\Authenticate($app->getContainer()->get('session')));
$app->get('/secure/status', '\App\Controller\SecureController:status')->add(new \App\Middleware\Authenticate($app->getContainer()->get('session')));
$app->get('/logout', '\App\Controller\AuthController:logout');

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function(Request $request) use ($container){
        $controller = new App\Controller\ExceptionController($container);
        return $controller->notFound($request);
    });

/*$app->get('/', function(Request $request, Response $response){
    $response->getBody()->write('hello, world !');
    return $response;
});*/

/*$app->get('/hello/bilal', function(Request $request, Response $response){
    $response->getBody()->write('hello bilal');
    return $response;
});

$app->get('/hello/{name}', function(Request $request, Response $response, array $args = []){
    //$response->getBody()->write(sprintf("hello, %s", ucfirst($arg['name'])));
    $html = $this->get('templating')->render('hello.html', ['name' => ucfirst($args['name'])]);
    $response->getBody()->write($html);
    return $response;
});*/
$app->run();