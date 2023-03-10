<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\driverMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\driverModel;

class driverController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new driverMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewDrivers(Request $request, Response $response)
    {

        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new driverModel();
        $user = $this->getUserModel();

        $this->render($userType . "/drivers/view", "View Drivers", [
            'model' => $model,
            'user' => $user,
        ]);
    }


   

}

