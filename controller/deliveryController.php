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

        $drivers = driverModel::getAllData(['ccID' => $user->ccID]);

        $sql2 = "SELECT deliveredBy,COUNT(*) as count FROM subdelivery WHERE deliveredBy IN (SELECT employeeID FROM driver WHERE ccID = :ccID) AND status = 'Ongoing' GROUP BY deliveredBy";
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
            $subdelivery->update(['subdeliveryID' => $data['subdeliveryID']],['deliveredBy' => $data['driverID'],'status' => 'Ongoing','distance' => $data['distance']]);
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
            $this->rollbackTransaction();
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

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function completeDelivery(Request $request, Response $response) : void {
        $data = $request->getJsonData()['data'];

        try {
            $this->startTransaction();

            // function to calculate nextdelivery stage
            $this->calculateNextDeliveryStage($data['subdeliveryID'],$data['process']);
            $this->commitTransaction();
//            $this->rollbackTransaction();

            // send success message upon successful completion
            $this->sendJson(['status' => 1, 'message' => 'Delivery completion was saved successfully']);
        }
        catch (\PDOException $e) {

            // send error message upon failure and rollback the transaction
            $this->rollbackTransaction();
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }

    }

    /**
     * @param string $subdeliveryID
     * @param string $process
     * @return void
     */
    private function calculateNextDeliveryStage(string $subdeliveryID, string $process) : void {

        // first get the subdelivery model
        $subdelivery = subdeliveryModel::getModel(['subdeliveryID' => $subdeliveryID]);

        // check whether the delivery stage is the final one or not
        if($subdelivery->deliveryStage === 1) {

            // if it is the final stage, then complete the delivery
            $this->complete($subdelivery,$process);

        }
        else {

            // if it is not the final stage, then update the delivery stage and set the next process
            $this->setNextProcess($subdelivery,$process);

        }

    }

    /**
     * @param subdeliveryModel $subdelivery
     * @param string $process
     * @return void
     */
    private function complete(subdeliveryModel $subdelivery, string $process) : void {

        // get the timesamp to be used for completion
        $completed = date('Y-m-d H:i:s');

        // update the subdelivery and delivery as completed
        subdeliveryModel::updateAsCompleted($subdelivery->subdeliveryID,$completed);
        deliveryModel::updateDeliveryAsCompleted($subdelivery->deliveryID,$completed);

        // complete the process, here process means donation, accepted request or cc donation
        $this->completeProcess($subdelivery,$process,$completed);

        // log the transaction on inventory logs
        $this->logtransactionComplete($subdelivery,$process);

        // if the process is cc donation then update the cc donation as completed
        if($process === 'ccdonation') {

            // send the notification to logistic officer
            $this->setNotification(
                "Your donation has been delivered",
                'Delivery Completed',
                $subdelivery->end,'',
                'delivery',$subdelivery->subdeliveryID);
            return;

        }

        // if it is of other 2 types, set the relevant SMS for relevant user
        $this->sendSMSByUserID(
            $process === "acceptedRequest" ? "Your delivery has been completed. Please check your dashboard for more details" : "Your donation has been delivered. Please check your dashboard for more details",
            $process === 'donation' ? $subdelivery->start : $subdelivery->end);

        // and send the notification for the relevant user
        $this->setNotification(
            $process === 'acceptedRequest' ? 'Your delivery has been completed. For any complaint please report via system or contact your community center' : 'Your donation has been delivered',
            'Delivery Completed',
            $process === 'donation' ? $subdelivery->start : $subdelivery->end,'',
            'delivery',$subdelivery->subdeliveryID);

    }

    /**
     * @param subdeliveryModel $subdelivery
     * @param string $process
     * @return void
     */
    private function logtransactionComplete(subdeliveryModel $subdelivery, string $process) : void {

        $sql = "";

        // set the sql statement according to the process
        switch ($process) {
            case "donation":
                $sql = "SELECT * FROM donation WHERE deliveryID = :deliveryID";
                break;
            case "acceptedRequest":
                $sql = "SELECT * FROM acceptedrequest WHERE deliveryID = :deliveryID";
                break;
            case "ccdonation":
                $sql = "SELECT * FROM ccdonation WHERE deliveryID = :deliveryID";
                break;
        }

        //fetch relevant data
        $stmnt = deliveryModel::prepare($sql);
        $stmnt->bindValue(':deliveryID',$subdelivery->deliveryID);
        $stmnt->execute();
        $data = $stmnt->fetch(\PDO::FETCH_ASSOC);

        // log the transaction in inventory logs according to the process
        switch ($process) {
            case 'donation':
                inventorylog::logCollectionOfDonationFromDonor($data['donationID'],$data['donateTo']);
                inventoryModel::updateInventoryAfterDonation($data);
                break;
            case 'acceptedRequest':
                inventorylog::logPickupFromCC($data['acceptedID'],$subdelivery->end);
                break;
            case 'ccdonation':
                inventorylog::logCCdonation($data['ccDonationID'],$data['fromCC'],$data['toCC']);
                break;
        }

    }

    /**
     * @param subdeliveryModel $subdelivery
     * @param string $process
     * @param string $completedDate
     * @return bool
     */
    private function completeProcess(subdeliveryModel $subdelivery, string $process, string $completedDate) : bool {

        $sql = "";

        //
        switch ($process) {
            case "donation":
                $sql = "UPDATE donation SET deliveryStatus = 'Completed' WHERE deliveryID = :deliveryID";
                break;
            case "acceptedRequest":
                $sql = "UPDATE acceptedrequest SET deliveryStatus = 'Completed' WHERE deliveryID = :deliveryID";
                break;
            case "ccdonation":
                $sql = "UPDATE ccdonation SET deliveryStatus = 'Completed',completedDate = '$completedDate' WHERE deliveryID = :deliveryID";
                break;
        }

        // then update the process as completed
        $stmnt = deliveryModel::prepare($sql);
        $stmnt->bindValue(':deliveryID',$subdelivery->deliveryID);
        $stmnt->execute();
        return true;

    }

    /**
     * @param subdeliveryModel $subdelivery
     * @param string $process
     * @return bool
     */
    private function setNextProcess(subdeliveryModel $subdelivery, string $process) : bool {

        // get the time stamp for the completion
        $completed = date('Y-m-d H:i:s');

        // update the subdelivery as completed
        subdeliveryModel::updateAsCompleted($subdelivery->subdeliveryID,$completed);
        $nextSubdelivery = new subdeliveryModel();

        // check the current stage and set the next stage
        if($subdelivery->deliveryStage === 2) {

            // if it is the 2nd stage, then set the next stage as final stage
            $nextSubdelivery->saveFinalStagedetails($subdelivery);

            // send SMS and notifications to relevant users
            $this->sendSMSByUserID(
                'Your delivery arrived at your community center. Please expect delivery soon',
                $nextSubdelivery->end);

            $this->setNotification(
                'Your delivery arrived at your community center. Please expect delivery soon',
                'Your delivery arrived at your community center.',
                $nextSubdelivery->end,'',
                'delivery',$nextSubdelivery->subdeliveryID);

        }
        else {

            // if it is the 3rd stage, then set the next stage as 2nd stage
            $nextSubdelivery->save2ndStagedetails($subdelivery);

            // send SMS and notifications to relevant users
            $this->sendSMSByUserID(
                "Your delivery arrived at your community center. It will be transferred to donee's community center soon",
                $subdelivery->start);

            $this->setNotification(
                "Your delivery arrived at your community center. It will be transferred to donee's community center soon",
                'Your delivery arrived at your community center.',
                $subdelivery->start,'',
                'delivery',$nextSubdelivery->subdeliveryID);
        }

        // log the transaction
        $this->logtransactionNext($subdelivery,$process);
        return true;
    }

    /**
     * @param subdeliveryModel $subdelivery
     * @param string $process
     * @return void
     */
    private function logtransactionNext(subdeliveryModel $subdelivery, string $process): void
    {

        // only accepted request process has more than one stage
        // retrieve directly from the accepted request table
        $sql = "SELECT * FROM acceptedrequest WHERE deliveryID = :deliveryID";
        $stmnt = deliveryModel::prepare($sql);
        $stmnt->bindValue(':deliveryID',$subdelivery->deliveryID);
        $stmnt->execute();
        $data = $stmnt->fetch(\PDO::FETCH_ASSOC);

        // log the transaction according to the process
        // switch between the 3rd stage and 2nd stage
        switch ($subdelivery->deliveryStage) {
            case 3:

                // if the delivery was from donor
                inventorylog::logCollectionFromDonor($data['acceptedID'],$data['acceptedBy']);
                break;
            case 2:

                // here check whether the delivery was from donor or from another CC
                if(str_contains($subdelivery->start,'donor')) {
                    inventorylog::logCollectionFromDonor($data['acceptedID'],$data['acceptedBy']);
                }
                else if ($data['acceptedBy'] === $subdelivery->start){
                    inventorylog::logDeliveryBetween2CCs($subdelivery->deliveryID,$data['acceptedID']);
                }

                break;

        }

    }


    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function requestToReassign(Request $request, Response $response): void
    {
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

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterDeliveries(Request $request, Response $response) : void {

        $data = $request->getJsonData();
        $process = $data['process'];
        $filters = $data['filters'];
        $sort = $data['sort'];

        try {
            $this->sendJson(array_merge(logisticModel::getDeliveriesOfLogisticOfficerFilteredAndSorted($process,$filters,$sort),['destinations' => subdeliveryModel::getDestinations(),'subcategories' => donationModel::getAllSubcategories()]));
        }
        catch (\PDOException $e) {
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function deliveryPopupDriver(Request $request, Response $response) : void {

        $subdeliveryID = $request->getJsonData()['subdeliveryID'];

        try{
            $subdelivery = subdeliveryModel::getDeliveryDetailsByID($subdeliveryID);

            $this->sendJson(['status' => 1,'subdeliveryDetails' => subdeliveryModel::getModel(['subdeliveryID' => $subdeliveryID]) , 'destinationAddresses' => deliveryModel::getDestinationAddresses()]);
        }
        catch(\PDOException $e){
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterCompletedDeliveries(Request $request, Response $response) : void {

        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];

        try {
            $this->sendJson([
                'status' => 1,
                'deliveries' => deliveryModel::getCompletedDeliveriesByDriverIDFilteredAndSorted($_SESSION['user'], $filters, $sort),
                'destinations' => deliveryModel::getDestinationAddresses(),
                'subcategories' => donationModel::getAllSubcategories()
            ]);
        }
        catch (\PDOException $e) {
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterAssignedDeliveries(Request $request, Response $response) : void {

        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];

        try {
            $this->sendJson([
                'status' => 1,
                'deliveries' => deliveryModel::getAssignedDeliveriesFilteredAndSorted($_SESSION['user'], $filters, $sort),
                'destinations' => deliveryModel::getDestinationAddresses(),
                'subcategories' => donationModel::getAllSubcategories()
            ]);
        }
        catch (\PDOException $e) {
            $this->sendJson(['status' => 0, 'message' => $e->getMessage()]);
        }
    }


}