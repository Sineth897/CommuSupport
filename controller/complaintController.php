<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\complaintMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\complaintModel;
<<<<<<< HEAD

class complaintController extends Controller
{
    public function __construct(string $func,Request $request, Response $response)
    {
        $this-> middleware=new complaintMiddleware();
        parent::__construct($func,$request,$response);
    }

    protected function viewComplaints(Request $request,Response $response){
        $model = new complaintModel();
        $userType = $this->getUserType();
        $user = $this->getUserModel();
        $this->render($userType . "/complaints/view",'Complaints',[
            'complaints'=> $model,
            'user' => $user
        ]);
=======
use app\models\userModel;

class complaintController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new complaintMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewComplaint(Request $request, Response $response)
    {
        $complaints = new complaintModel();
        $user = $this->getUserModel();
        $this->render('cho/complaints/view','Complaints',[
            "complaints"=> $complaints,
            "user"=>$user

        ]);

>>>>>>> eff85e44986d3bc01499410308913423699b159b
    }
}