<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;


class ShopController extends Controller
{
    public function defShop(Request $request, Response $response)
    {
        $bikes = json_decode(file_get_contents(__DIR__ . '/../../data/bikes.json'),true);
        return $this->render($response, 'defshop.html', ['bikes' => $bikes]);
    }

    public function details(Request $request, Response $response, array $args = [])
    {
        $bikes = json_decode(file_get_contents(__DIR__ . '/../../data/bikes.json'),true);
        $key = array_search($args['id'], array_column($bikes, 'id'));
        if($key === false) {
            throw new HttpNotFoundException($request, $response);
        }
        return $this->render($response, 'details.html', ['bike' => $bikes[$key]]);
    }
}