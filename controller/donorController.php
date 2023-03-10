<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\donorMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\donorModel;

class donorController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new donorMiddleware();
        parent::__construct($func, $request, $response);
    }


    protected function viewDonors(Request $request, Response $response)
    {
        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new donorModel();
        $user = $this->getUserModel();
        $this->render($userType . "/donors/view", "View Donors", [
            'model' => $model,
            'user' => $user
        ]);
    }
}
