<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\deliveryMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\deliveryModel;
use app\models\driverModel;
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
                $sql = "SELECT *,CONCAT(d.amount,' ',c.scale) as amount FROM subdelivery s LEFT JOIN donation d ON d.deliveryID = s.deliveryID INNER JOIN subcategory c ON d.item = c.subcategoryID WHERE d.donationID = :processID AND s.subdeliveryID = :deliveryID";
                break;
            case "acceptedRequests":
                $sql = "SELECT *,CONCAT(r.amount,' ',c.scale) as amount FROM subdelivery s LEFT JOIN acceptedrequest r ON r.deliveryID = s.deliveryID INNER JOIN subcategory c ON r.item = c.subcategoryID WHERE r.acceptedID = :processID AND s.subdeliveryID = :deliveryID";
                break;
            case "ccDonations":
                $sql = "SELECT *,CONCAT(d.amount,' ',c.scale) FROM subdelivery s LEFT JOIN ccdonation d ON d.deliveryID = s.deliveryID INNER JOIN subcategory c ON d.item = c.subcategoryID WHERE d.ccDonationID = :processID AND s.subdeliveryID = :deliveryID";
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
//        $sql1 = "SELECT * FROM driver ";

        $stmt1 = deliveryModel::prepare($sql1);
        $stmt1->bindValue(':ccID',$user->ccID);
        $stmt1->execute();
        $drivers = $stmt1->fetchAll(\PDO::FETCH_ASSOC);

        $sql2 = "SELECT deliveredBy,COUNT(*) as count FROM subdelivery WHERE deliveredBy IN (SELECT employeeID FROM driver WHERE ccID = :ccID) AND status = 'Ongoing'";
//        $sql2 = "SELECT deliveredBy,COUNT(*) as count FROM subdelivery WHERE deliveredBy IN (SELECT employeeID FROM driver) AND status = 'Ongoing'";
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
        $this->render('driver/completed/view','Completed Deliveries');
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
}