<?php

namespace app\controller;

use app\core\Controller;
use app\core\exceptions\forbiddenException;
use app\core\middlewares\managerMiddleware;
use app\core\middlewares\profileMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\doneeModel;

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

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function doneeProfile(Request $request, Response $response) : void {

        $this->checkLink($request);
        $donee = new doneeModel();

        $this->render("donee/profile","Donee profile", [
            'donee' => $donee
        ]);

    }
}