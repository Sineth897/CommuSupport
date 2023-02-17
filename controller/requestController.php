<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\requestMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\requestModel;

class requestController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new requestMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewRequests(Request $request,Response $response) {

        $userType = $this->getUserType();
        $model = new requestModel();
        $user = $this->getUserModel();
        $this->render($userType ."/request/view","View Requests",[
            'model' => $model,
            'user' => $user
        ]);
    }

    protected function postRequest(Request $request,Response $response) {

        $requestmodel = new requestModel();

        if($request->isPost()) {
            $requestmodel->getData($request->getBody());
            if($requestmodel->validate($request->getBody()) && $requestmodel->save()) {
                $this->setFlash('success','Request posted successfully');
                $response->redirect('/donee/request');
                return;
            }
        }

        $this->render('donee/request/create','Post a request',[
            'requestmodel' => $requestmodel,
        ]);
    }
    

}