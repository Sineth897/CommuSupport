<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\ccDonationMiddleware;
use app\core\middlewares\ccMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\ccDonationModel;
use app\models\ccModel;
use app\models\deliveryModel;
use app\models\inventorylog;
use app\models\inventoryModel;
use app\models\logisticModel;
use app\models\subdeliveryModel;

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

    // function to accept CC donations which are posted by the logistic officers of other community centers
    // it calls 2 other functions to get the details of the donation and to accept the donation according to data received from the JSON request
    protected function acceptCCDonation(Request $request,Response $response) : void {

        $data = $request->getJsonData();
        $do = $data['do'];

        try{
            $this->startTransaction();
            switch($do) {
                // case to get the details of the donation
                case 'retrieve':
                    $this->getCCDonationDetailsForAcceptPopup($data['ccDonationID']);
                    break;
                // case to accept the donation
                case 'accept':
                    $this->acceptCCDonationFromPopup($data['ccDonationID']);
                    break;
            }
            $this->commitTransaction();
        }
        catch (\Exception $e) {
            // in case of failure, sending the error message as a JSON object
            $this->rollbackTransaction();
            $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

    }

    // function to get the details of the donation
    private function getCCDonationDetailsForAcceptPopup(string $ccDonationID) : void {
        // sql query to get details of the donation with item info
        $sql = "SELECT *,CONCAT(c.amount,' ',s.scale) AS amount FROM `ccdonation` c INNER JOIN subcategory s on c.item = s.subcategoryID";
        $ccDonation = ccDonationModel::runCustomQuery($sql,['ccDonationID' => $ccDonationID])[0];

        // getting the model of the currently logged in logistic officer
        $logistic = logisticModel::getModel(['employeeID' => $_SESSION['user']]);
        $item = $ccDonation['item'];

        // query to get the available amount of the item in the community center
        $sql = "SELECT CONCAT(i.amount,' ',s.scale) AS amount FROM inventory i INNER JOIN subcategory s ON i.subcategoryID = s.subcategoryID";

        // sending relevant data as a JSON object
        $this->sendJson([
            'status' => 1,
            'ccDonation' => $ccDonation,
            'communitycenters' => ccModel::getCCs(),
            'available' => inventoryModel::runCustomQuery($sql,['i.subcategoryID' => $item,'ccID' => $logistic->ccID]),
        ]);
    }

    private function acceptCCDonationFromPopup(string $ccDonationID) : void {

        //taking relevant CCd donation details
        $ccDonation = ccDonationModel::getModel(['ccDonationID' => $ccDonationID]);

        //getting model of currently logged in logistic officer's community center
        $logistic = logisticModel::getModel(['employeeID' => $_SESSION['user']]);
        $fromCC = ccModel::getModel(['ccID' => $logistic->ccID]);

        //getting the model of the community center from which the donation is requested
        $toCC = ccModel::getModel(['ccID' => $ccDonation->toCC]);

        // setting relevant data to be saved in the database
        $data = [
            'subdeliveryCount' => 1, // since delivery is from one CC to another
            'deliveryStage' => 1,
            'start' => $fromCC->ccID,
            'fromLongitude' => $fromCC->longitude,
            'fromLatitude' => $fromCC->latitude,
            'end' => $toCC->ccID,
            'toLongitude' => $toCC->longitude,
            'toLatitude' => $toCC->latitude,
            'deliveryID' =>  substr(uniqid('delivery',true),0,23),
        ];

        // getting delivery and subdelivery models to save data
        $delivery = new deliveryModel();
        $subdelivery = new subdeliveryModel();

        // loading data to models to be saved
        $delivery->getData($data);
        $subdelivery->getData($data);

        // updating  CC donation and saving delivery and subdelivery
        $delivery->save();
        ccDonationModel::acceptCCDonation($ccDonationID,$fromCC->ccID,$delivery->deliveryID);
        $subdelivery->save();

        // send notification to the logistic officer who posted the notification
        $postedLogistic = logisticModel::getModel(['ccID' => $ccDonation->toCC]);
        $subcategories = ccDonationModel::getAllSubcategories();
        $msg = "Your donation for {$subcategories[$ccDonation->item]} has been accepted by {$fromCC->city} community center";
        $this->setNotification($msg,'Donation has been accepted',$postedLogistic->employeeID,'','ccDonation',$ccDonationID);

        // mark the transaction on the inventory Log
        inventorylog::logAcceptingCCDonation($ccDonationID);

        // Deduct the amount from the inventory
        inventoryModel::updateInventoryAfterAcceptingCCDontion($fromCC->ccID,$ccDonation->item,$ccDonation->amount);

        $this->sendJson([
            'status' => 1,
            'message' => 'Donation accepted successfully',
            'model' => $ccDonation,
            'id' => $ccDonationID,
        ]);
    }

    protected function CCDonationPopup(Request $request,Response $response) : void {

        $ccDonationID = $request->getJsonData()['ccDonationID'];

        try {
            $this->sendJson([
                'status' => 1,
                // getting the details of the donation
                'ccDonation' => ccDonationModel::getCCDonationInfoWithItemInfo($ccDonationID),
                // getting the details of the delivery along with each subdelivery
                'deliveries' => deliveryModel::getDeliveryInfoOfCCDonation($ccDonationID),
                // getting community centers with their ID and city as key value pair
                'communitycenters' => ccModel::getCCs(),
            ]);
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

    }

    protected function filterCCDonations(Request $request, Response $response) : void {

        // getting the data from the JSON request
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];

        // getting the model of the currently logged in logistic officer
        $logistic = logisticModel::getModel(['employeeID' => $_SESSION['user']]);

        try{
            $this->sendJson([
                'status' => 1,
                // getting the filtered donations
                'ccDonations' => ccDonationModel::getCCDonationsBelongsToLogisticOfficerFilteredAndSorted($filters,$sort),
                // getting the community centers with their ID and city as key value pair
                'communitycenters' => ccModel::getCCs(),
                // send community center ID of the currently logged in logistic officer
                'CC' => $logistic->ccID,
            ]);
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

    }

}