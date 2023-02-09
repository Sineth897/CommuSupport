<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\donationMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\donationModel;

class donationController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new donationMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewDonations(Request $request,Response $response) {

        $userType = $this->getUserType();
        $model = new donationModel();
        $user = $this->getUserModel();
        $this->render($userType ."/donation/view","View donation",[
            'model' => $model,
            'user' => $user
        ]);
    }


   
}