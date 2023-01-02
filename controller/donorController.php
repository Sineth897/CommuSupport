<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\donorMiddleware;
use app\core\Request;
use app\core\Response;

class donorController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new donorMiddleware();
        parent::__construct($func, $request, $response);
    }


    protected function viewDonors(Request $request, Response $response)
    {
        $user = $this->getUserType();
        $this->render($user . "/donors/view");
    }
}
