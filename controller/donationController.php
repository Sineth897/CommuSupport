<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\donationMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\donationModel;
use app\models\donorModel;

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

    protected function createDonation(Request $request,Response $response) {
        $model = new donationModel();
        $user = donorModel::getModel(['donorID' =>Application::session()->get('user')]);

        if($request->isPost()) {
            $model->getData($request->getBody());
            if($model->validate($request->getBody()) && $user->createDonation($request->getBody())) {
                $this->setFlash('success','Donation created successfully');
                $model->reset();
            }
            $this->setFlash('error','Validation failed');
        }

        $this->render("donor/donation/create","Donate to a community center",[
            'model' => $model,
            'user' => $user
        ]);
    }


   
}