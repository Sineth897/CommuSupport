<?php

require_once __DIR__ . './../vendor/autoload.php';

use app\core\Application;
use app\controller\guestController;

$app = new Application(dirname(__DIR__) . "/Commusupport");

$app->router->get('/', [guestController::class, "home"]);
$app->router->get('/contact', function () {
    return "Contact";
});



$app->run();
