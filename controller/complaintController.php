<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\complaintMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\complaintModel;
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

    }
}