<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\complaintMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\complaintModel;

class complaintController extends Controller
{
    public function __construct(string $func, Request $request, Response $response)
    {
        $this->middleware = new complaintMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewComplaints(Request $request, Response $response)
    {
        $userType = $this->getUserType();
        $complaints = new complaintModel();
        $user = $this->getUserModel();
        $this->render($userType . '/complaints/view','Complaints',[
            "complaints"=> $complaints,
            "user"=>$user
        ]);
    }
}