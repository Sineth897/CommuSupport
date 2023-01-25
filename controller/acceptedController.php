<?php

namespace app\controller;

use app\core\Controller;
// use app\core\middlewares\acceptedMiddleware;
use app\core\middlewares\acceptedMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\acceptedModel;

class acceptedController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new acceptedMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewAcceptedRequests(Request $request,Response $response) {

        $userType = $this->getUserType();
        $model = new acceptedModel();
        $user = $this->getUserModel();
        $this->render($userType ."/request/accepted","View Accepted Requests",[
            'model' => $model,
            'user' => $user
        ]);
    }


    

}