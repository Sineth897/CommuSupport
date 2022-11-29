<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\eventMiddleware;
use app\core\Request;
use app\core\Response;

class eventController extends Controller
{
    public function __construct(string $func,Request $request,Response $response)
    {
        $this->getUserType();
        if(method_exists($this, $func)) {
            $this->middleware = new eventMiddleware();
            $this->middleware->execute($func, $this->userType);
            $this->$func($request,$response);
        } else {
            throw new \Exception('Method does not exist');
        }

    }

    public function viewEvents(Request $request,Response $response) {

        $user = $this->getUserType();

        $this->render($user . "/events");

    }

}