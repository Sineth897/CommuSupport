<?php

require_once __DIR__ . './vendor/autoload.php';

use app\controller\guestController;
use app\controller\loginController;
use app\core\Application;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'userClass' => \app\models\loginForm::class,
    "db" => [
        "dsn" => $_ENV['DB_DSN'],
        "user" => $_ENV['DB_USER'],

    ]
];

$app = new Application(dirname(__DIR__) . "/CommuSupport", $config);

$app->router->get('/', function($request, $response){
    $controller = new guestController("home", $request, $response);
});

$app->router->get('/form', function($request,$response){
    $controller = new guestController("form", $request, $response);
});

$app->router->get('/login', function($request,$response){
    $controller = new guestController("login", $request, $response);
});

$app->router->get('/login/employee', function($request,$response){
    $controller = new loginController("employeeLogin", $request, $response);
});

$app->router->get('/login/user', function($request,$response){
    $controller = new loginController("userLogin", $request, $response);
});



$app->router->post('/form', function($request,$response){
    $controller = new guestController("handleForm", $request, $response);
});

$app->router->post('/login/employee', function($request,$response){
    $controller = new loginController("employeeLogin", $request, $response);
});

$app->router->post('/login/user', function($request,$response){
    $controller = new guestController("login", $request, $response);
});








$app->run();
