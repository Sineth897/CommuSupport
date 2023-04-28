<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\ccDonationMiddleware;
use app\core\middlewares\ccMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\ccDonationModel;
use app\models\ccModel;

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
        $this->render("logistic/CCdonation/view","View Donations",[
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
        $this->render("logistic/CCdonation/create","View Donations",[
            'model' => $model,
            'user' => $user
        ]);
    }

    protected function acceptCCDonation(Request $request,Response $response) {

        $data = $request->getJsonData();
        $do = $data['do'];

        try{
            switch($do) {
                case 'retrieve':
                    $this->getCCDonationDetailsForAcceptPopup($data['ccDonationID']);
                    break;
            }
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

    }

    private function getCCDonationDetailsForAcceptPopup($ccDonationID) : void {
        $sql = "SELECT *,CONCAT(c.amount,' ',s.scale) AS amount FROM `ccdonation` c INNER JOIN subcategory s on c.item = s.subcategoryID";


        $this->sendJson([
            'status' => 1,
            'ccDonation' => ccDonationModel::runCustomQuery($sql,['ccDonationID' => $ccDonationID])[0],
            'communitycenters' => ccModel::getCCs()
        ]);
    }

}