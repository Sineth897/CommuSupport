<?php

require_once __DIR__ . './vendor/autoload.php';


use app\controller\loginController;
use app\controller\redirectController;
use app\core\Application;
use app\models\userModel;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'userClass' => userModel::class,
    "db" => [
        "dsn" => $_ENV['DB_DSN'],
        "user" => $_ENV['DB_USER'],
        ],
    "root" => [
        "username"  =>  $_ENV['DB_ADMIN_USER'],
        "password" => $_ENV['DB_ADMIN_PASS']
        ],
];

$app = new Application(dirname(__DIR__) . "/CommuSupport", $config);

$app->router->get('/', function($request, $response){
    $controller = new redirectController("redirectHome", $request, $response);
});

$app->router->get('/login/employee', function($request,$response){
    $controller = new loginController("employeeLogin", $request, $response);
});

$app->router->get('/login/user', function($request,$response){
    $controller = new loginController("userLogin", $request, $response);
});

$app->router->get('/manager/events', function ($request, $response) {
   $controller = new \app\controller\eventController("viewEvents",$request,$response);
});













$app->router->post('/login/employee', function($request,$response){
    $controller = new loginController("employeeLogin", $request, $response);
});

$app->router->post('/login/user', function($request,$response){
    $controller = new loginController("userLogin", $request, $response);
});

$app->router->post('/logout', function($request,$response){
    $controller = new loginController("logout", $request, $response);
});






$app->run();
