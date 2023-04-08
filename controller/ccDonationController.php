<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\ccDonationMiddleware;
use app\core\middlewares\ccMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\ccDonationModel;

class ccDonationController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new ccDonationMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewCCDonations(Request $request,Response $response) {

        $this->checkLink($request);

        $model = new ccDonationModel();
        $user = $this->getUserModel();
        $this->render("logistic/donation/view","View Donations",[
            'model' => $model,
            'user' => $user
        ]);
    }

    protected function createCCDonation(Request $request,Response $response) {

        $this->checkLink($request);

        $model = new ccDonationModel();


        try {
            if($request->isPost()) {
                $model->getData($request->getBody());
                if($model->validate($request->getBody()) && $model->save()) {
                    $this->setFlash("success","Donation created successfully");
                    $model->reset();
                    $_POST['category'] = "";
                }
                else {
                    $this->setFlash("error","Data validation failed");
                }
            }
        }
        catch (\Exception $e) {
            $this->setFlash("error",$e->getMessage());
        }



        $user = $this->getUserModel();
        $this->render("logistic/donation/create","View Donations",[
            'model' => $model,
            'user' => $user
        ]);
    }

}