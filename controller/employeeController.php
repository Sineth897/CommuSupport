<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\employeeMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\employeeModel;
use app\models\logisticModel;
use app\models\managerModel;

class employeeController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new employeeMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewEmployees(Request $request,Response $response) {

        $userType = $this->getUserType();
        $logistics = new logisticModel();
        $managers = new managerModel();
        $this->render($userType ."/employees/view","View employees",[
            'logistics' => $logistics,
            'managers' => $managers,
        ]);
    }


   
}