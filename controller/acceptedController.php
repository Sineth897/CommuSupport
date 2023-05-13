<?php

namespace app\controller;

use app\core\Controller;
// use app\core\middlewares\acceptedMiddleware;
use app\core\exceptions\forbiddenException;
use app\core\exceptions\methodNotFound;
use app\core\middlewares\acceptedMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\acceptedModel;

class acceptedController extends Controller
{

    /**
     * @param $func
     * @param Request $request
     * @param Response $response
     * @throws forbiddenException
     * @throws methodNotFound
     */
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new acceptedMiddleware();
        parent::__construct($func, $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function viewAcceptedRequests(Request $request, Response $response) : void {

        // get the usertype, acceptedRequest model and user model
        $userType = $this->getUserType();
        $model = new acceptedModel();
        $user = $this->getUserModel();

        // render the view, passing those variables
        $this->render($userType ."/request/accepted","View Accepted Requests",[
            'model' => $model,
            'user' => $user
        ]);
    }


    

}