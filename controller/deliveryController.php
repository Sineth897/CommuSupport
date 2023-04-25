<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\deliveryMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\acceptedModel;
use app\models\ccDonationModel;
use app\models\deliveryModel;
use app\models\donationModel;
use app\models\driverModel;
use app\models\inventorylog;
use app\models\inventoryModel;
use app\models\logisticModel;
use app\models\subdeliveryModel;

class deliveryController extends Controller
{
    public function __construct($func,Request $request,Response $response) {

        $this->middleware = new deliveryMiddleware();
        parent::__construct($func, $request, $response);

    }

    protected function viewDeliveries($request, $response)
    {
        $this->checkLink($request);

        $userType = $this->getUserType();
        $deliveries = new deliveryModel();
        $user = $this->getUserModel();

        $this->render($userType . '/deliveries/view', 'Deliveries', [
            'deliveries' => $deliveries,
            'user' => $user,
        ]);
    }

    protected function deliveryPopup(Request $request,Response $response) {

        $data = $request->getJsonData()['data'];
        $deliveryType = $data['deliveryType'];
        $deliveryID = $data['deliveryID'];

        $sql = "";

        switch ($deliveryType) {
            case "directDonations":
                $sql = "SELECT *,CONCAT(d.amount,' ',c.scale) as amount,s.status as deliveryStatus FROM subdelivery s LEFT JOIN donation d ON d.deliveryID = s.deliveryID INNER JOIN subcategory c ON d.item = c.subcategoryID WHERE d.donationID = :processID AND s.subdeliveryID = :deliveryID";
                break;
            case "acceptedRequests":
                $sql = "SELECT *,CONCAT(r.amount,' ',c.scale) as amount,s.status as deliveryStatus FROM subdelivery s LEFT JOIN acceptedrequest r ON r.deliveryID = s.deliveryID INNER JOIN subcategory c ON r.item = c.subcategoryID WHERE r.acceptedID = :processID AND s.subdeliveryID = :deliveryID";
                break;
            case "ccDonations":
                $sql = "SELECT *,CONCAT(d.amount,' ',c.scale) as amount,s.status as deliveryStatus FROM subdelivery s LEFT JOIN ccdonation d ON d.deliveryID = s.deliveryID INNER JOIN subcategory c ON d.item = c.subcategoryID WHERE d.ccDonationID = :processID AND s.subdeliveryID = :deliveryID";
                break;
        }

        $stmt = deliveryModel::prepare($sql);

        $stmt->bindValue(':processID', $data['related']);
        $stmt->bindValue(':deliveryID',$deliveryID);

        try {
            $stmt->execute();
            $this->sendJson(['status' => 1, 'data' => $stmt->fetchAll(\PDO::FETCH_ASSOC)[0],'drivers' => $this->getDriverDetails(),'addresses' => $this->getUserAddress(),'deliveryType' => $deliveryType]);
        }
        catch (\PDOException $e) {
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }

    }

    private function getDriverDetails() : array {
        $user = logisticModel::getModel(['employeeID' => $_SESSION['user']]);

        $sql1 = "SELECT * FROM driver WHERE ccID = :ccID ";

        $stmt1 = deliveryModel::prepare($sql1);
        $stmt1->bindValue(':ccID',$user->ccID);
        $stmt1->execute();
        $drivers = $stmt1->fetchAll(\PDO::FETCH_ASSOC);

        $sql2 = "SELECT deliveredBy,COUNT(*) as count FROM subdelivery WHERE deliveredBy IN (SELECT employeeID FROM driver WHERE ccID = :ccID) AND status = 'Ongoing'";
        $stmt2 = deliveryModel::prepare($sql2);
        $stmt2->bindValue(':ccID',$user->ccID);
        $stmt2->execute();
        $count = $stmt2->fetchAll(\PDO::FETCH_KEY_PAIR);
        return array_map( fn($driver) => array_merge($driver,['active' => $count[$driver['employeeID']] ?? 0]),$drivers);
    }

    private function getUserAddress() : array {
        $sql = "SELECT donorID,address FROM donor UNION SELECT doneeID,address FROM donee UNION SELECT ccID,address FROM commusupport_db.communitycenter";
        $stmt = deliveryModel::prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    protected function assignDriver(Request $request,Response $response) {
        $data = $request->getJsonData()['data'];
        $subdelivery  = new subdeliveryModel();

        try {
            $this->startTransaction();
            //update relavant subdelivery record
            $subdelivery->update(['subdeliveryID' => $data['subdeliveryID']],['deliveredBy' => $data['driverID'],'status' => 'Ongoing']);
            //update relevant process using a private function defined in this controller
            $this->updateProcess($data['related'],$data['processID']);
            //send sms to the driver
            $this->sendSMSByUserID("You have been assigned a delivery. Please check your dashboard for more details",$data['driverID']);
            $this->setNotification('Please check delivery dashboard for more details','You have been assigned a delivery.',$data['driverID'],'','delivery',$data['subdeliveryID']);
            //notify the user
            $subdelivery = $subdelivery->retrieve(['subdeliveryID' => $data['subdeliveryID']])[0];
            if(str_contains($subdelivery['start'],'donee') || str_contains($subdelivery['start'],'donor')) {
                $this->sendSMSbyuserID("Your delivery has been assigned to a driver. Please check your dashboard for more details",$subdelivery['start']);
                $this->setNotification('Get package ready for pickup.','Your delivery has been assigned to a driver.',$subdelivery['start'],'',$data['related'],$data['processID']);
            }
            if (str_contains($subdelivery['end'],'donee') || str_contains($subdelivery['end'],'donor')) {
                $this->sendSMSbyuserID("Your delivery has been assigned to a driver. Please check your dashboard for more details",$subdelivery['end']);
                $this->setNotification('Expect delivery to be delivered very soon.','Your delivery has been assigned to a driver.',$subdelivery['end'],'',$data['related'],$data['processID']);
            }

            $this->commitTransaction();
            $this->sendJson(['status' => 1, 'message' => 'Delivery Assigned Successfully',]);
        }
        catch (\PDOException $e) {
            $this->rollbackTransaction();
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }

    }

    private function updateProcess(string $process,string $processID) {
        $sql = "";
        switch ($process) {
            case "directDonations":
                $sql = "UPDATE donation SET commusupport_db.donation.deliveryStatus = 'Ongoing' WHERE donationID = :processID";
                break;
            case "acceptedRequests":
                $sql = "UPDATE acceptedrequest SET commusupport_db.acceptedrequest.deliveryStatus = 'Ongoing' WHERE acceptedID = :processID";
                break;
            case "ccDonations":
                $sql = "UPDATE ccdonation SET commusupport_db.ccdonation.deliveryStatus = 'Ongoing' WHERE ccDonationID = :processID";
                break;
        }
        $stmnt = deliveryModel::prepare($sql);
        $stmnt->bindValue(':processID',$processID);
        $stmnt->execute();
        return true;

    }

    protected function completedDeliveries(Request $request,Response $response) {

        $deliveryModel = new deliveryModel();
        $user = new driverModel();

        $this->render('driver/completed/view','Completed Deliveries',[
            'deliveryModel' => $deliveryModel,
            'user' => $user
        ]);
    }

    protected function getRouteDetails(Request $request,Response $response) {
        $subdeliveryID = $request->getJsonData()['data']['subdeliveryID'];

        try {
            $this->sendJson(['status' => 1, 'data' => subdeliveryModel::getAllData(['subdeliveryID' => $subdeliveryID])[0]]);
        }
        catch (\PDOException $e) {
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    protected function completeDelivery(Request $request,Response $response) {
        $data = $request->getJsonData()['data'];

        try {
            $this->startTransaction();
            $this->calculateNextDeliveryStage($data['subdeliveryID'],$data['process']);
            $this->commitTransaction();
//            $this->rollbackTransaction();
            $this->sendJson(['status' => 1, 'message' => 'Delivery completion was saved successfully']);
        }
        catch (\PDOException $e) {
            $this->rollbackTransaction();
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    private function calculateNextDeliveryStage(string $subdeliveryID,string $process) {

        $subdelivery = subdeliveryModel::getModel(['subdeliveryID' => $subdeliveryID]);

        if($subdelivery->deliveryStage === 1) {
            $this->complete($subdelivery,$process);
        }
        else {
            $this->setNextProcess($subdelivery,$process);
        }
    }

    private function complete(subdeliveryModel $subdelivery,string $process) {
        $completed = date('Y-m-d H:i:s');
        subdeliveryModel::updateAsCompleted($subdelivery->subdeliveryID,$completed);
        deliveryModel::updateDeliveryAsCompleted($subdelivery->deliveryID,$completed);
        $this->completeProcess($subdelivery,$process,$completed);
        $this->logtransactionComplete($subdelivery,$process);
        $this->sendSMSByUserID($process === "acceptedRequest" ? "Your delivery has been completed. Please check your dashboard for more details" : "Your donation has been delivered. Please check your dashboard for more details",$process === 'donation' ? $subdelivery->start : $subdelivery->end);
        $this->setNotification('Delivery Completed',$process === 'acceptedRequest' ? 'Your delivery has been completed. For any complaint please report via system or contact your community center' : 'Your donation has been delivered',$process === 'donation' ? $subdelivery->start : $subdelivery->end,'','delivery',$subdelivery->subdeliveryID);
    }

    private function logtransactionComplete(subdeliveryModel $subdelivery,string $process) {
        $sql = "";
        switch ($process) {
            case "donation":
                $sql = "SELECT * FROM donation WHERE deliveryID = :deliveryID";
                break;
            case "acceptedRequest":
                $sql = "SELECT * FROM acceptedrequest WHERE deliveryID = :deliveryID";
                break;
            case "ccDonation":
                $sql = "SELECT * FROM ccdonation WHERE deliveryID = :deliveryID";
                break;
        }
        $stmnt = deliveryModel::prepare($sql);
        $stmnt->bindValue(':deliveryID',$subdelivery->deliveryID);
        $stmnt->execute();
        $data = $stmnt->fetch(\PDO::FETCH_ASSOC);

        switch ($process) {
            case 'donation':
                inventorylog::logCollectionOfDonationFromDonor($data['donationID'],$data['donateTo']);
                inventoryModel::updateInventoryAfterDonation($data);
                break;
            case 'acceptedRequest':
                inventorylog::logPickupFromCC($data['acceptedID'],$data['donateTo']);
                break;
            case 'ccDonation':
                inventorylog::logCCdonation($data['ccDonationID'],$data['fromCC'],$data['toCC']);
                break;
        }
    }

    private function completeProcess(subdeliveryModel $subdelivery,string $process,string $completedDate) {
        $sql = "";
        switch ($process) {
            case "donation":
                $sql = "UPDATE donation SET deliveryStatus = 'Completed' WHERE deliveryID = :deliveryID";
                break;
            case "acceptedRequest":
                $sql = "UPDATE acceptedrequest SET deliveryStatus = 'Completed' WHERE deliveryID = :deliveryID";
                break;
            case "ccDonation":
                $sql = "UPDATE ccdonation SET deliveryStatus = 'Completed',completedDate = '$completedDate' WHERE deliveryID = :deliveryID";
                break;
        }
        $stmnt = deliveryModel::prepare($sql);
        $stmnt->bindValue(':deliveryID',$subdelivery->deliveryID);
        $stmnt->execute();
        return true;
    }

    private function setNextProcess(subdeliveryModel $subdelivery,string $process) {
        $completed = date('Y-m-d H:i:s');
        subdeliveryModel::updateAsCompleted($subdelivery->subdeliveryID,$completed);
        $nextSubdelivery = new subdeliveryModel();

        if($subdelivery->deliveryStage === 2) {
            $nextSubdelivery->saveFinalStagedetails($subdelivery);
            $this->sendSMSByUserID('Your delivery arrived at your community center. Please expect delivery soon',$nextSubdelivery->end);
            $this->setNotification('Your delivery arrived at your community center. Please expect delivery soon','Your delivery arrived at your community center. Please expect delivery soon',$nextSubdelivery->end,'','delivery',$nextSubdelivery->subdeliveryID);
        }
        else {
            $nextSubdelivery->save2ndStagedetails($subdelivery);
            $this->sendSMSByUserID('Your delivery arrived at your community center. Please expect delivery soon',$subdelivery->start);
            $this->setNotification('Your delivery arrived at your community center. Please expect delivery soon','Your delivery arrived at your community center. Please expect delivery soon',$subdelivery->start,'','delivery',$nextSubdelivery->subdeliveryID);
        }
        $this->logtransactionNext($subdelivery,$process);
        return true;
    }

    private function logtransactionNext(subdeliveryModel $subdelivery,string $process) {
        $sql = "SELECT * FROM acceptedrequest WHERE deliveryID = :deliveryID";
        $stmnt = deliveryModel::prepare($sql);
        $stmnt->bindValue(':deliveryID',$subdelivery->deliveryID);
        $stmnt->execute();
        $data = $stmnt->fetch(\PDO::FETCH_ASSOC);

        switch ($subdelivery->deliveryStage) {
            case 3:
                inventorylog::logCollectionFromDonor($data['acceptedID'],$data['donateTo']);
                break;
            case 2:
                if(str_contains($subdelivery->start,'donor')) {
                    inventorylog::logCollectionFromDonor($data['acceptedID'],$data['donateTo']);
                }
                else {
                    inventorylog::logDeliveryBetween2CCs($subdelivery->deliveryID);
                }

                break;
        }
    }


    protected function requestToReassign(Request $request,Response $response) {
        $data = $request->getJsonData()['data'];
        $subdelivery = new subdeliveryModel();

        try {
            $this->startTransaction();

            $sql = "SELECT l.employeeID,d.name FROM subdelivery s INNER JOIN driver d ON s.deliveredBy = d.employeeID INNER JOIN logisticofficer l on d.ccID = l.ccID WHERE s.subdeliveryID = :subdeliveryID";
            $stmnt = deliveryModel::prepare($sql);
            $stmnt->bindValue(':subdeliveryID',$data['subdeliveryID']);
            $stmnt->execute();
            $result = $stmnt->fetch(\PDO::FETCH_ASSOC);

            if($data['do'] === 'Request to Re-Assign' ) {
                $subdelivery->update(['subdeliveryID' => $data['subdeliveryID']],['status' => 'Reassign Requested']);
                $this->setNotification("{$result['name']} requested reassign on delivery with id {$data['subdeliveryID']}",'Delivery Reassign Requested',$result['employeeID'],'','delivery',$subdelivery->subdeliveryID);
                $this->sendJson(['status' => 1, 'message' => 'Delivery Reassign Requested Successfully', 'innerHTML' => 'Cancel Re-assign Request']);


            }
            else {
                $subdelivery->update(['subdeliveryID' => $data['subdeliveryID']],['status' => 'Ongoing']);
                $this->setNotification("{$result['name']} canceled reassign request on delivery with id {$data['subdeliveryID']}",'Delivery Reassign Request Canceled',$result['employeeID'],'','delivery',$subdelivery->subdeliveryID);
                $this->sendJson(['status' => 1, 'message' => 'Delivery Reassign Request Canceled Successfully', 'innerHTML' => 'Request to Re-Assign']);
            }

            $this->commitTransaction();

        }
        catch (\PDOException $e) {
            $this->rollbackTransaction();
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }

    }

}