<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\donationMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\ccModel;
use app\models\deliveryModel;
use app\models\donationModel;
use app\models\donorModel;
use app\models\subdeliveryModel;

class donationController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new donationMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewDonations(Request $request,Response $response) {

        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new donationModel();
        $user = $this->getUserModel();
        $this->render($userType ."/donation/view","View donation",[
            'model' => $model,
            'user' => $user
        ]);
    }

    protected function createDonation(Request $request,Response $response) {

        $data = $request->getJsonData();

        //model creation and retrieval
        $model = new donationModel();
        $donor = donorModel::getModel(['donorID' => $_SESSION['user']]);
        $cc = ccModel::getModel(['ccID' => $donor->ccID]);
        $delivery = new deliveryModel();
        $subdelivery = new subdeliveryModel();

        //generate delivery id
        $deliveryID = substr(uniqid('delivery',true),0,23);
        //merge all needed data to one array
        $data = array_merge($data,$this->donationDetails($donor,$cc),['deliveryID' => $deliveryID],$this->deliveryDetails($donor,$cc),$this->subdeliveryDetails($donor,$cc));
        //loading data to models
        $model->getData($data);
        $delivery->getData($data);
        $subdelivery->getData($data);

        try {
            $this->startTransaction();
            $model->save();
            $delivery->save();
            $subdelivery->save();
            $this->commitTransaction();
            $this->sendJson(['status' => 1 , 'msg' => 'Donation created successfully']);
        }
        catch (\Exception $e) {
            $this->rollbackTransaction();
            $this->sendJson((['status' => 0 , 'msg' => 'Donation creation failed']));
        }
    }

    private function donationDetails(donorModel $donor,ccModel $cc): array {
        return [
            'createdBy' => $donor->donorID,
            'donateTo' => $cc->ccID,
        ];
    }

    private function deliveryDetails(donorModel $donor,ccModel $cc): array {
        return [
            'subdeliveryCount' => 1,
            'start' => $donor->donorID,
            'end' => $cc->ccID,
        ];
    }

    private function subdeliveryDetails(donorModel $donor,ccModel $cc): array {
        return [
            'deliveryStage' => 1,
            'fromLongitude' => $donor->longitude,
            'fromLatitude' => $donor->latitude,
            'toLongitude' => $cc->longitude,
            'toLatitude' => $cc->latitude,
        ];
    }
   
}