<?php

date_default_timezone_set('Asia/Kolkata');

require_once __DIR__ . '/vendor/autoload.php';


use app\controller\eventController;
use app\controller\loginController;
use app\controller\redirectController;
use app\controller\registerController;
use app\core\Application;
use app\models\userModel;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
    'userClass' => userModel::class,
    "db" => [
        "dsn" => $_ENV['DB_DSN'],
        "user" => $_ENV['DB_USER'],
        "password" => $_ENV['DB_PASS'],
        ],
    "root" => [
        "username"  =>  $_ENV['DB_ADMIN_USER'],
        "password" => $_ENV['DB_ADMIN_PASS']
        ],
    "sms" => [
        'id' => $_ENV['SMS_ID'],
        'pw' => $_ENV['SMS_PW']
    ],
];

$app = new Application(dirname(__DIR__) . "/CommuSupport", $config);



//*************************Guest get and post methods*************************//
////Guest landing page
$app->router->get('/', function($request, $response) {
    $controller = new redirectController("redirectHome", $request, $response);
});

//Guest employee login page
$app->router->get('/login/employee', function($request,$response) {
    $controller = new loginController("employeeLogin", $request, $response);
});

//Guest user login page
$app->router->get('/login/user', function($request,$response) {
    $controller = new loginController("userLogin", $request, $response);
});

//Guest locked account
$app->router->get('/login/locked', function($request,$response) {
    $controller = new loginController("lockedAccount", $request, $response);
});

//General user login post
$app->router->get('/logout', function($request,$response) {
    $controller = new loginController("logout", $request, $response);
});

//login and logout for employees and other users
$app->router->post('/login/employee', function($request,$response) {
    $controller = new loginController("employeeLogin", $request, $response);
});
$app->router->post('/login/user', function($request,$response) {
    $controller = new loginController("userLogin", $request, $response);
});
$app->router->post('/logout', function($request,$response) {
    $controller = new loginController("logout", $request, $response);
});

//forget password for all users
$app->router->get('/forgetpassword', function($request,$response) {
    $controller = new loginController("forgetPassword", $request, $response);
});
$app->router->post('/forgetpassword', function($request,$response) {
    $controller = new loginController("forgetPassword", $request, $response);
});

//Register methods for all users
$app->router->get('/register/donee', function($request,$response) {
    $controller = new registerController("registerDonee", $request, $response);
});
$app->router->post('/register/donee', function($request,$response) {
    $controller = new registerController("registerDonee", $request, $response);
});

$app->router->get('/register/donor', function($request,$response) {
    $controller = new registerController("registerDonor", $request, $response);
});
$app->router->post('/register/donor', function($request,$response) {
    $controller = new registerController("registerDonor", $request, $response);
});
//mobile verification
$app->router->get('/verify/mobile', function($request,$response) {
    $controller = new registerController("verifyMobile", $request, $response);
});
$app->router->get('/test', function($request,$response) {
    $controller = new redirectController("test", $request, $response);
});
$app->router->post('/test', function($request,$response) {
    $controller = new redirectController("test", $request, $response);
});

$app->router->get('/communitycenters', function($request,$response) {
    $controller = new \app\controller\ccController('getCoordinates',$request,$response);
});
$app->router->post('/communitycenters', function($request,$response) {
    $controller = new \app\controller\ccController('getCoordinates',$request,$response);
});
$app->router->get('/verifyMobile', function($request,$response) {
    $controller = new loginController('verifyMobile',$request,$response);
});
$app->router->post('/verifyMobile', function($request,$response) {
    $controller = new loginController('verifyMobile',$request,$response);
});

































//*************************Manager get and post methods*************************//
//manager home page
$app->router->get('/manager', function($request,$response) {
    $controller = new redirectController("redirectHome", $request, $response);
});

//manager view event
$app->router->get('/manager/events', function ($request, $response) {
   $controller = new \app\controller\eventController("viewEvents",$request,$response);
});
//manager filter event
$app->router->post('/manager/event/filter', function ($request, $response) {
    $controller = new \app\controller\eventController("filterEvents",$request,$response);
});
//Event popUP
$app->router->post('/manager/event/popup', function ($request, $response) {
    $controller = new \app\controller\eventController("eventPopUp",$request,$response);
});
//Manager event creation
$app->router->get('/manager/event/create', function ($request, $response) {
    $controller = new \app\controller\eventController("createEvent",$request,$response);
});
$app->router->post('/manager/event/create', function ($request, $response) {
    $controller = new \app\controller\eventController("createEvent",$request,$response);
});
//manager event update
$app->router->post('/manager/event/update', function ($request, $response) {
    $controller = new \app\controller\eventController("updateEvent",$request,$response);
});

//Manager view drivers
$app->router->get('/manager/drivers', function ($request, $response) {
    $controller = new \app\controller\driverController("viewDrivers",$request,$response);
});

$app->router->post('/manager/drivers/filter', function ($request, $response) {
    $controller = new \app\controller\driverController("filterDrivers",$request,$response);
});

//Manager driver registration
$app->router->get('/manager/drivers/register', function ($request, $response) {
    $controller = new \app\controller\registerController("registerDriver",$request,$response);
});

$app->router->post('/manager/drivers/register', function ($request, $response) {
    $controller = new \app\controller\registerController("registerDriver",$request,$response);
});

$app->router->post('/manager/driver/popup', function ($request,$response) {
    $controller = new \app\controller\driverController("driverPopup",$request,$response);
});

//Manager view donees
$app->router->get('/manager/donees', function ($request, $response) {
    $controller = new \app\controller\doneeController("viewDonees",$request,$response);
});
$app->router->post('/manager/donee/getdata', function ($request, $response) {
    $controller = new \app\controller\doneeController("getData",$request,$response);
});
$app->router->post('/manager/donee/verify', function ($request, $response) {
    $controller = new \app\controller\doneeController("verifyDonee",$request,$response);
});
$app->router->post('/manager/donees/filter', function ($request, $response) {
    $controller = new \app\controller\doneeController("filterDonees",$request,$response);
});
$app->router->post('/manager/donee/popup', function ($request,$response) {
    $controller = new \app\controller\doneeController("doneePopup",$request,$response);
});

//Manager view donors
$app->router->get('/manager/donors', function ($request, $response) {
    $controller = new \app\controller\donorController("viewDonors",$request,$response);
});
$app->router->post('/manager/donors/filter', function ($request, $response) {
    $controller = new \app\controller\donorController("filterDonors",$request,$response);
});
$app->router->post('/manager/donor/popup', function ($request,$response) {
    $controller = new \app\controller\donorController("donorPopup",$request,$response);
});

//Manager view request
$app->router->get('/manager/requests', function ($request, $response) {
    $controller = new \app\controller\requestController("viewRequests",$request,$response);
});
$app->router->post('/manager/requests/popup', function ($request, $response) {
    $controller = new \app\controller\requestController("requestPopup",$request,$response);
});
$app->router->post('/manager/request/approve', function ($request, $response) {
    $controller = new \app\controller\requestController("setApproval",$request,$response);
});
//Manager view donation
$app->router->get('/manager/donations', function ($request, $response) {
    $controller = new \app\controller\donationController("viewDonations",$request,$response);
});
$app->router->post('/manager/donations/popup', function ($request, $response) {
    $controller = new \app\controller\donationController("donationPopup",$request,$response);
});
$app->router->post('/manager/donations/filter', function ($request, $response) {
    $controller = new \app\controller\donationController("filterDonationsEmployee",$request,$response);
});
//Manager view donation
$app->router->get('/manager/profile', function ($request, $response) {
    $controller = new \app\controller\profileController("viewProfile",$request,$response);
});







































//*************************Donee get and post methods*************************//
$app->router->get('/donee/request', function ($request, $response) {
    $controller = new \app\controller\requestController("viewRequests",$request,$response);
});

$app->router->get('/donee/request/create', function($request,$response) {
    $controller = new \app\controller\requestController('postRequest',$request,$response);
});

$app->router->post('/donee/request/create', function($request,$response) {
    $controller = new \app\controller\requestController('postRequest',$request,$response);
});

$app->router->get('/donee/communitycenters', function ($request, $response) {
    $controller = new \app\controller\ccController("viewCC",$request,$response);
});

$app->router->get('/donee/events', function ($request, $response) {
    $controller = new \app\controller\eventController("viewEvents",$request,$response);
});

$app->router->post('/donee/event/popup', function ($request,$response) {
    $controller = new eventController('eventPopUpUser',$request,$response);
});

$app->router->post('/donee/event/filter', function ($request, $response) {
    $controller = new \app\controller\eventController("filterEventsUser",$request,$response);
});

$app->router->post('/donee/event/markParticipation', function ($request, $response) {
    $controller = new \app\controller\eventController("participate",$request,$response);
});

$app->router->get('/donee/complaints', function($request,$response) {
    $controller = new \app\controller\complaintController('viewComplaint',$request,$response);
});






















































//*************************Donor get and post methods*************************//
//Donor view request
$app->router->get('/donor/requests', function ($request, $response) {
    $controller = new \app\controller\requestController("viewRequests",$request,$response);
});
$app->router->post('/donor/requests/popup', function ($request, $response) {
    $controller = new \app\controller\requestController("requestPopup",$request,$response);
});
$app->router->post('/donor/requests/accept', function ($request, $response) {
    $controller = new \app\controller\requestController("acceptRequest",$request,$response);
});
$app->router->post('/donor/requests/filter', function ($request, $response) {
    $controller = new \app\controller\requestController("filterRequests",$request,$response);
});

//donor view communitycenter
$app->router->get('/donor/communitycenters', function ($request, $response) {
    $controller = new \app\controller\ccController("viewCC",$request,$response);
});
//Donor accepted request
$app->router->get('/donor/acceptedrequests', function ($request, $response) {
    $controller = new \app\controller\acceptedController("viewAcceptedRequests",$request,$response);
});

///Donor view donation
$app->router->get('/donor/donations', function ($request, $response) {
    $controller = new \app\controller\donationController("viewDonations",$request,$response);
});

//Donor create donation
$app->router->post('/donor/donation/create', function ($request, $response) {
    $controller = new \app\controller\donationController("createDonation",$request,$response);
});

$app->router->post('/donor/donations/filter', function ($request, $response) {
    $controller = new \app\controller\donationController("filterDonations",$request,$response);
});

$app->router->post('/donor/donation/popup', function ($request, $response) {
    $controller = new \app\controller\donationController("donationPopup",$request,$response);
});

//Donor view event
$app->router->get('/donor/events', function($request,$response) {
    $controller = new eventController("viewEvents",$request,$response);
});
$app->router->post('/donor/event/popup', function ($request,$response) {
    $controller = new eventController('eventPopUpUser',$request,$response);
});
$app->router->post('/donor/event/filter', function ($request, $response) {
    $controller = new \app\controller\eventController("filterEventsUser",$request,$response);
});

$app->router->post('/donor/event/markParticipation', function ($request, $response) {
    $controller = new \app\controller\eventController("participate",$request,$response);
});

//Donor view complaints
$app->router->get('/donor/complaints', function($request,$response) {
    $controller = new \app\controller\complaintController('viewComplaint',$request,$response);
});







































//*************************Logistic get and post methods*************************//
//logistic view drivers
$app->router->get("/logistic/drivers", function ($request,$response) {
    $controller = new \app\controller\driverController("viewDrivers",$request,$response);
});

$app->router->post('/logistic/drivers/filter', function ($request,$response) {
    $controller = new \app\controller\driverController("filterDrivers",$request,$response);
});

$app->router->post('/logistic/driver/popup', function ($request,$response) {
    $controller = new \app\controller\driverController("driverPopup",$request,$response);
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

$app->router->get('/logistic/deliveries', function ($request,$response) {
    $controller = new \app\controller\deliveryController("viewDeliveries",$request,$response);
});

$app->router->post('/logistic/delivery/popup', function ($request,$response) {
    $controller = new \app\controller\deliveryController("deliveryPopup",$request,$response);
});

$app->router->post('/logistic/delivery/assign', function($request,$response) {
    $controller = new \app\controller\deliveryController("assignDriver",$request,$response);
});

$app->router->get('/logistic/requests', function ($request,$response) {
    $controller = new \app\controller\requestController("viewRequests",$request,$response);
});
$app->router->post('/logistic/requests/popup', function ($request, $response) {
    $controller = new \app\controller\requestController("requestPopup",$request,$response);
});
$app->router->post('/logistic/requests/accept', function ($request, $response) {
    $controller = new \app\controller\requestController("acceptRequest",$request,$response);
});
$app->router->post('/logistic/requests/filter', function ($request, $response) {
    $controller = new \app\controller\requestController("filterRequests",$request,$response);
});

$app->router->get('/logistic/CCdonations', function ($request,$response) {
    $controller = new \app\controller\ccDonationController("viewCCDonations",$request,$response);
});

$app->router->get("/logistic/CCdonations/create", function ($request,$response) {
    $controller = new \app\controller\ccDonationController("createCCDonation",$request,$response);
});

$app->router->post("/logistic/CCdonations/create", function ($request,$response) {
    $controller = new \app\controller\ccDonationController("createCCDonation",$request,$response);
});

$app->router->post('/logistic/inventory/getcurrentinventory', function ($request,$response) {
    $controller = new \app\controller\inventoryController("getCurrentInventory",$request,$response);
});

$app->router->get('/logistic/donations', function ($request,$response) {
    $controller = new \app\controller\donationController("viewDonations",$request,$response);
});
$app->router->post('/logistic/donation/popup', function ($request,$response) {
    $controller = new \app\controller\donationController("donationPopup",$request,$response);
});
$app->router->post('/logistic/donations/filter', function ($request,$response) {
    $controller = new \app\controller\donationController("filterDonationsEmployee",$request,$response);
});




























//*************************Driver get and post methods*************************//
$app->router->get('/driver/deliveries', function($request,$response) {
    $controller = new \app\controller\deliveryController('viewDeliveries',$request,$response);
});

$app->router->post('/driver/delivery/route', function ($request,$response) {
    $controller = new \app\controller\deliveryController('getRouteDetails',$request,$response);
});

$app->router->post('/driver/delivery/finish', function ($request,$response) {
    $controller = new \app\controller\deliveryController('completeDelivery',$request,$response);
});

$app->router->post('/driver/delivery/reassign', function ($request,$response) {
    $controller = new \app\controller\deliveryController('requestToReassign',$request,$response);
});

$app->router->get('/driver/deliveries/completed', function ($request,$response) {
    $controller = new \app\controller\deliveryController('completedDeliveries',$request,$response);
});






















































//*************************CHO get and post methods*************************//
//cho add a community center
$app->router->get("/cho/communitycenter/register", function($request,$response) {
    $controller = new \app\controller\registerController("registerCC",$request,$response);
});
$app->router->post("/cho/communitycenter/register", function ($request,$response) {
   $controller = new \app\controller\registerController('registerCC',$request,$response);
});
//cho views community center
$app->router->get("/cho/communitycenters", function($request,$response) {
   $controller = new \app\controller\ccController('viewCC',$request,$response);
});

//cho add a manager
$app->router->get("/cho/communitycenters/register/manager",function ($request,$response){
    $controller= new \app\controller\registerController("registerManager",$request,$response);
});
$app->router->post("/cho/communitycenters/register/manager",function ($request,$response){
    $controller= new \app\controller\registerController("registerManager",$request,$response);
});

//cho add a logistic
$app->router->get("/cho/communitycenters/register/logistic",function ($request,$response){
    $controller = new \app\controller\registerController("registerLogistic",$request,$response);
});
$app->router->post("/cho/communitycenters/register/logistic",function ($request,$response){
    $controller = new \app\controller\registerController("registerLogistic",$request,$response);
});

//cho view a complaint
$app->router->get("/cho/complaints",function($request,$response){
   $controller=new \app\controller\complaintController("viewComplaints",$request,$response);
});



































































//*************************Admin get and post methods*************************//
//Admin view cho
$app->router->get('/admin/communityheadoffices', function ($request, $response) {
    $controller = new \app\controller\choController("viewCho",$request,$response);
});
$app->router->post('/admin/communityheadoffices/popup', function ($request, $response) {
    $controller = new \app\controller\choController("choPopup",$request,$response);
});

//Admin register cho
$app->router->get('/admin/communityheadoffices/register', function ($request, $response) {
    $controller = new \app\controller\registerController("registerCho",$request,$response);
});

$app->router->post('/admin/communityheadoffices/register', function ($request, $response) {
    $controller = new \app\controller\registerController("registerCho",$request,$response);
});

//Admin view cc
$app->router->get('/admin/communitycenters', function ($request, $response) {
    $controller = new \app\controller\ccController("viewCC",$request,$response);
});

$app->router->post('/admin/communitycenters/filter', function ($request, $response) {
    $controller = new \app\controller\ccController("filterCC",$request,$response);
});

//Admin view employees
$app->router->get('/admin/employees', function ($request, $response) {
    $controller = new \app\controller\employeeController("viewEmployees",$request,$response);
});
//Admin view donation
$app->router->get('/admin/donations', function ($request, $response) {
    $controller = new \app\controller\donationController("viewDonations",$request,$response);
});

$app->router->post('/admin/donations/filter', function ($request, $response) {
    $controller = new \app\controller\donationController("filterDonationsAdmin",$request,$response);
});

//Admin view request
$app->router->get('/admin/requests', function ($request, $response) {
    $controller = new \app\controller\requestController("viewRequests",$request,$response);
});

$app->router->post('/admin/requests/filter', function ($request, $response) {
    $controller = new \app\controller\requestController("filterRequestsAdmin",$request,$response);
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

$app->router->post('/admin/drivers/filter', function ($request, $response) {
    $controller = new \app\controller\driverController("filterDriversAdmin",$request,$response);
});

$app->router->get("/admin/events", function ($request, $response) {
    $controller = new eventController("viewEvents", $request, $response);
});

$app->router->post("/admin/events/filter", function ($request, $response) {
    $controller = new eventController("filterEventsAdmin", $request, $response);
});

$app->router->get('/admin/donees', function($request,$response) {
    $controller = new \app\controller\doneeController('viewDonees',$request,$response);
});

$app->router->post('/admin/donees/filter', function($request,$response) {
    $controller = new \app\controller\doneeController('doneesFilterAdmin', $request, $response);
});

$app->router->get('/admin/donors', function($request,$response) {
    $controller = new \app\controller\donorController('viewDonors', $request,$response);
});

$app->router->post('/admin/donors/filter', function($request,$response) {
    $controller = new \app\controller\donorController('donorsFilterAdmin', $request, $response);
});













$app->run();
