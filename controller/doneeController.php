<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\doneeMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\doneeModel;

class doneeController extends Controller
{

    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new doneeMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewDonees(Request $request, Response $response)
    {
        $userType = $this->getUserType();
        $model = new doneeModel();
        $user = $this->getUserModel();
        $this->render($userType . "/donees/view", "View Donees",[
            'model' => $model,
            'user' => $user
        ]);
    }

}