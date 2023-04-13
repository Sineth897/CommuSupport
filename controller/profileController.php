<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\managerMiddleware;
use app\core\middlewares\profileMiddleware;
use app\core\Request;
use app\core\Response;

class profileController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new  profileMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewProfile(Request $request, Response $response)
    {
        $this->checkLink($request);
        $model = $this->getUserModel();

        $userType = $this->getUserType();

        $this->render($userType."/profile","$userType profile", [
            'model' => $model
        ]);

    }
}