<?php

use Bramus\Router\Router;

$router = new Router();

$router->setNamespace('\App\Controllers');

$router->get('/', 'HomeController@index');
$router->post('/filterSales', 'HomeController@filterSales');
$router->post('/', 'HomeController@uploadSalesData');

$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo "404: Route not found";
});
$router->run();