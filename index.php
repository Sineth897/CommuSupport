<?php

require_once __DIR__ . '/vendor/autoload.php';


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



//*************************Guest get and post methods*************************//
//Guest landing page
$app->router->get('/', function($request, $response){
    $controller = new redirectController("redirectHome", $request, $response);
});

//Guest employee login page
$app->router->get('/login/employee', function($request,$response){
    $controller = new loginController("employeeLogin", $request, $response);
});

//Guest user login page
$app->router->get('/login/user', function($request,$response){
    $controller = new loginController("userLogin", $request, $response);
});

//Guest locked account
$app->router->get('/login/locked', function($request,$response){
    $controller = new loginController("lockedAccount", $request, $response);
});

//General user login post
$app->router->get('/logout', function($request,$response){
    $controller = new loginController("logout", $request, $response);
});

//login and logout for employees and other users
$app->router->post('/login/employee', function($request,$response){
    $controller = new loginController("employeeLogin", $request, $response);
});

$app->router->post('/login/user', function($request,$response){
    $controller = new loginController("userLogin", $request, $response);
});

$app->router->post('/logout', function($request,$response){
    $controller = new loginController("logout", $request, $response);
});

































//*************************Manager get and post methods*************************//
//manager home page
$app->router->get('/manager', function($request,$response){
    $controller = new redirectController("redirectHome", $request, $response);
});

//manager view events
$app->router->get('/manager/events', function ($request, $response) {
   $controller = new \app\controller\eventController("viewEvents",$request,$response);
});

//manager filter event
$app->router->post('/manager/events/filter', function ($request, $response) {
    $controller = new \app\controller\eventController("filterEvents",$request,$response);
});

//Manager event creation
$app->router->get('/manager/events/create', function ($request, $response) {
    $controller = new \app\controller\eventController("createEvent",$request,$response);
});

$app->router->post('/manager/events/create', function ($request, $response) {
    $controller = new \app\controller\eventController("createEvent",$request,$response);
});

//Manager view drivers
$app->router->get('/manager/drivers', function ($request, $response) {
    $controller = new \app\controller\driverController("viewDrivers",$request,$response);
});

//Manager driver registration
$app->router->get('/manager/drivers/register', function ($request, $response) {
    $controller = new \app\controller\registerController("registerDriver",$request,$response);
});

$app->router->post('/manager/drivers/register', function ($request, $response) {
    $controller = new \app\controller\registerController("registerDriver",$request,$response);
});

//Manager view donees
$app->router->get('/manager/donees', function ($request, $response) {
    $controller = new \app\controller\doneeController("viewDonees",$request,$response);
});

//Manager view donors
$app->router->get('/manager/donors', function ($request, $response) {
    $controller = new \app\controller\donorController("viewDonors",$request,$response);
});























//*************************Donee get and post methods*************************//






































































//*************************Donor get and post methods*************************//
//Donor view request
$app->router->get('/donor/requests', function ($request, $response) {
    $controller = new \app\controller\requestController("viewRequests",$request,$response);
});




































































//*************************Logistic get and post methods*************************//
//logistic view drivers
$app->router->get("/logistic/drivers", function ($request,$response) {
    $controller = new \app\controller\driverController("viewDrivers",$request,$response);
});

//logistic view inventory
$app->router->get("/logistic/inventory", function ($request,$response) {
    $controller = new \app\controller\inventoryController("viewInventory",$request,$response);
});

$app->router->post('/logistic/inventory/add', function ($request,$response) {
    $controller = new \app\controller\inventoryController("addInventory",$request,$response);
});

$app->router->post('/logistic/inventory/filter', function ($request,$response) {
    $controller = new \app\controller\inventoryController("filterInventory",$request,$response);
});



















































//*************************Driver get and post methods*************************//





































































//*************************CHO get and post methods*************************//
//cho add a community center
$app->router->get("cho/communitycenter/register", function($request,$response) {
    $controller = new \app\controller\registerController("registerCC",$request,$response);
});
$app->router->get("/cho/communitycenter/register", function ($request,$response) {
   $controller = new \app\controller\registerController('registerCC',$request,$response);
});
//cho views community center
$app->router->get("/cho/communitycenters", function($request,$response) {
   $controller = new \app\controller\ccController('viewCC',$request,$response);
});



























































//*************************Admin get and post methods*************************//
//Admin view cho
$app->router->get('/admin/communityheadoffices', function ($request, $response) {
    $controller = new \app\controller\choController("viewCho",$request,$response);
});

//Admin view cc
$app->router->get('/admin/communitycenters', function ($request, $response) {
    $controller = new \app\controller\ccController("viewCC",$request,$response);
});

//Admin register cho
$app->router->get('/admin/communityheadoffices/register', function ($request, $response) {
    $controller = new \app\controller\registerController("registerCho",$request,$response);
});

$app->router->post('/admin/communityheadoffices/register', function ($request, $response) {
    $controller = new \app\controller\registerController("registerCho",$request,$response);
});
//Admin view employees
$app->router->get('/admin/employees', function ($request, $response) {
    $controller = new \app\controller\employeeController("viewEmployees",$request,$response);
});
//Admin view donations
$app->router->get('/admin/donations', function ($request, $response) {
    $controller = new \app\controller\donationController("viewDonations",$request,$response);
});
//Admin view request
$app->router->get('/admin/requests', function ($request, $response) {
    $controller = new \app\controller\requestController("viewRequests",$request,$response);
});
//Admin view logistics
$app->router->get('/admin/logistics', function ($request, $response) {
    $controller = new \app\controller\logisticController("viewLogistics",$request,$response);
});
//Admin view managers
$app->router->get('/admin/managers', function ($request, $response) {
    $controller = new \app\controller\managerController("viewManagers",$request,$response);
});
//Admin view  drivers
$app->router->get('/admin/drivers', function ($request, $response) {
    $controller = new \app\controller\driverController("viewDrivers",$request,$response);
});


















































$app->run();
