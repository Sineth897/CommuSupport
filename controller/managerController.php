<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\managerMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\managerModel;

class managerController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new  managerMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewManagers(Request $request,Response $response) {
        $userType = $this->getUserType();
        $model = new managerModel();
        $user = $this->getUserModel();
        $this->render($userType ."/manager/view","View managers",[
            'model' => $model,
            'user' => $user
        ]);
    }



}