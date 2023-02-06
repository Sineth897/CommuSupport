<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\logisticMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\logisticModel;

class logisticController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new  logisticMiddleware();
        parent::__construct($func, $request, $response);
    }
    protected function viewLogistics(Request $request,Response $response) {
        $userType = $this->getUserType();
        $model = new logisticModel();
        $user = $this->getUserModel();
        $this->render($userType ."/logistic/view","View logistics",[
            'model' => $model,
            'user' => $user
        ]);
    }
   
}