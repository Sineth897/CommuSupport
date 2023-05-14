<?php

namespace app\controller;

use app\core\Controller;
use app\core\DbModel;
use app\core\exceptions\forbiddenException;
use app\core\exceptions\methodNotFound;
use app\core\middlewares\requestMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\acceptedModel;
use app\models\ccModel;
use app\models\deliveryModel;
use app\models\doneeModel;
use app\models\donorModel;
use app\models\inventorylog;
use app\models\logisticModel;
use app\models\managerModel;
use app\models\requestModel;
use app\models\subdeliveryModel;

class requestController extends Controller
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
        $this->middleware = new requestMiddleware();
        parent::__construct($func, $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function viewRequests(Request $request, Response $response) : void {

        // check the link
        $this->checkLink($request);

        // get user type, request model and relevant user's model
        $userType = $this->getUserType();
        $model = new requestModel();
        $accepted = new acceptedModel();
        $user = $this->getUserModel();

        // pass all variables to the view
        $this->render($userType ."/request/view","View Requests",[
            'model' => $model,
            'accepted' => $accepted,
            'user' => $user
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function postRequest(Request $request, Response $response) : void {

        // check link
        $this->checkLink($request);

        $requestmodel = new requestModel();

        // if the request is a post request
        if($request->isPost()) {

            // load data to the model from the request
            $requestmodel->getData($request->getBody());

            // validate and save on the database
            if($requestmodel->validate($request->getBody()) && $requestmodel->save()) {

                // if successful set the flash message and redirect to view requests page
                $this->setFlash('success','Request posted successfully');
                $response->redirect('/donee/request');
                return;

            }

        }

        $this->render('donee/request/create','Post a request',[
            'requestmodel' => $requestmodel,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function requestPopup(Request $request, Response $response) : void {

        // is request is by donee
        if($_SESSION['userType'] === 'donee') {
            $this->getRequestPopupForDonee($request);
            return;
        }

        // else get data
        $where = $request->getJsonData();

        // if the request is for an accepted request
        if(str_contains($where['r.requestID'],'accepted')) {
            $this->getAcceptedRequestPopup($where);
            return;
        }

        // prepare WHERE clause
        $where = array_key_first($where) . " = '" . $where[array_key_first($where)] . "'";

        try {
            $sql = "SELECT *,CONCAT(amount,' ',scale) as amount FROM request r 
                            INNER JOIN subcategory s ON r.item = s.subcategoryID 
                            INNER JOIN category c ON s.categoryID = c.categoryID WHERE $where";

            $stmnt = requestModel::prepare($sql);
            $stmnt->execute();
            $requestDetails = $stmnt->fetch(\PDO::FETCH_ORI_FIRST); // fetch only the first row

            // get the donee model from the database
            $donee = doneeModel::getModel(['doneeID' => $requestDetails['postedBy']]);

            // if donee is an individual
            if($donee->type === 'Individual') {
                $donee = $donee->retrieveWithJoin('doneeIndividual','doneeID',['donee.doneeID' => $donee->doneeID]);
                $donee = $donee[0];
                $donee['name'] = $donee['fname'] . " " . $donee['lname'];
            }
            else {
                $donee = $donee->retrieveWithJoin('doneeOrganization','doneeID',['donee.doneeID' => $donee->doneeID])[0];
            };

            // send the response
            $this->sendJson([
                'status' => 1,
                'requestDetails' => $requestDetails,
                'donee' => $donee
            ]);

        }
        catch (\PDOException $e) {
            $this->sendJson(['status' => 0 , 'message' => $e->getMessage()]);
        }

    }

    /**
     * @param $where
     * @return void
     */
    private function getAcceptedRequestPopup($where) : void {

        //get request details
        $sql = "SELECT *,CONCAT(amount,' ',scale) as amount FROM acceptedrequest r 
                    INNER JOIN subcategory s ON r.item = s.subcategoryID 
                    INNER JOIN category c ON s.categoryID = c.categoryID";

        // get delivery details
        $sqldeliveries = "SELECT s.*,d.*,s.status AS deliveryStatus FROM subdelivery s 
                    LEFT JOIN delivery d ON s.deliveryID = d.deliveryID 
                    LEFT JOIN acceptedrequest r ON d.deliveryID = r.deliveryID";

        try {
            $this->sendJson([
                'status' => 1 ,
                'requestDetails' => acceptedModel::runCustomQuery($sql,['acceptedID' => $where['r.requestID']])[0],
                'deliveries' => subdeliveryModel::runCustomQuery($sqldeliveries,['r.acceptedID' => $where['r.requestID']])
            ]);
        }
        catch (\PDOException $e) {
            $this->sendJson([
                'status' => 0 ,
                'message' => $e->getMessage()
            ]);
        }

    }

    private function getRequestPopupForDonee(Request $request) : void {

        // get data from js fetch request
        $data = $request->getJsonData();

        // initialize sql statement to empty string to be modified according to the delivery status
        $sql =  "";
        $deliveries = "SELECT s.*,d.*,s.status AS deliveryStatus FROM subdelivery s LEFT JOIN delivery d ON s.deliveryID = d.deliveryID LEFT JOIN acceptedrequest r ON d.deliveryID = r.deliveryID";

        switch ($data['deliveryStatus']) {

            // if delivery is completed then get the aggregate of the all accepted requests
            case 'Completed':
                $sql = "SELECT *,CONCAT(SUM(r.amount),' ',s.scale) AS amount,COUNT(r.requestID) AS users FROM acceptedrequest r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID WHERE r.requestID = '{$data['requestID']}' GROUP BY r.requestID ";
                break;

            // if delivery is not completed then get the details of the accepted request
            default:
                $sql = "SELECT *,CONCAT(r.amount,' ',s.scale) AS amount FROM acceptedrequest r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID WHERE r.acceptedID = '{$data['acceptedID']}'";
                break;
        }

        // send the json response
        try {
            $this->sendJson(['status' => 1 , 'requestDetails' => acceptedModel::runCustomQuery($sql)[0], 'deliveries' => subdeliveryModel::runCustomQuery($deliveries,['r.acceptedID' => $data['acceptedID']])]);
        }
        catch (\PDOException $e) {
            $this->sendJson(['status' => 0 , 'message' => $e->getMessage(),'sql' => substr($sql,50)]);
        }

    }

    protected function setApproval(Request $request,Response $response) {
        $data = $request->getJsonData();
        $func = $data['do'];
        unset($data['do']);
        $data = $data['data'];
        try {
            switch($func) {
                case 'approve':
                    $this->approveRequest($data);
                    break;
                case 'reject':
                    $this->rejectRequest($data);
                    break;
            }
            $this->sendJson([
                'success' => true
            ]);
        }
        catch (\PDOException $e) {
            $this->rollbackTransaction();
            $this->sendJson($e->getMessage());
        }
    }

    private function approveRequest($data) {

        $this->startTransaction();
        $requestmodel = requestModel::getModel(['requestID' => $data['requestID']]);
        $requestmodel->update($data,['approval' => 'Approved','approvedDate' => date('Y-m-d')]);
        $this->setNotification('Your request has been approved. You can view. request details under active requests page','Request Approved',$requestmodel->postedBy,'','request',$data['requestID']);
        $this->sendSMSByUserID('Your request has been approved. You can view. request details under active requests page',$requestmodel->postedBy);

        $this->commitTransaction();
    }

    private function rejectRequest($data) {

        $this->startTransaction();

        $requestID = $data['requestID'];
        $req = requestModel::getModel(['requestID' => $requestID]);
        $req->rejectRequest($data['rejectedReason']);
        $donee = doneeModel::getModel(['doneeID' => $req->postedBy]);
        $this->setNotification('Your request has been rejected. Reason : {$data["rejectedReason"]}','Request Rejected',$donee->doneeID,'','request',$requestID);
        $this->sendSMSByUserID("Your request has been rejected. Reason : {$data['rejectedReason']}",$donee->doneeID);

        $this->commitTransaction();
    }

    protected function acceptRequest(Request $request,Response $response) {
        $data = $request->getJsonData();
        $reqID = $data['requestID'];


        $deliveryID = substr(uniqid('delivery',true),0,23);
        $req = requestModel::getModel(['requestID' => $data['requestID']]);
        $acceptor = $this->acceptedUserDetails();
        $delivery = new deliveryModel();
        $subdelivery = new subdeliveryModel();
        $donee = doneeModel::getModel(['doneeID' => $req->postedBy]);

        try{
            $data = array_merge($data,
                ['deliveryID' => $deliveryID,],
                $acceptor['data'],
                $this->subdeliveryDetails($acceptor['user'],$donee));


            $this->startTransaction();
            $req->getData($data);
            $acceptedRequest = $req->accept();
            $acceptedRequest->getData($data);
            $subdelivery->getData($data);
            $delivery->getData(array_merge($data,$this->deliveryDetails($acceptor['user'],$donee)));

            $result = $delivery->save() && $acceptedRequest->save() && $subdelivery->save();

            inventorylog::logInventoryAcceptingRequest($acceptedRequest->acceptedID);
            $this->setNotification('Your request has been accepted. Check accepted requests for more details','Request is accepted',$req->postedBy,'donee','request',$acceptedRequest->acceptedID);

            if(!$result) {
                $this->rollbackTransaction();
                $this->sendJson(["success"=> 0,"error" => "Error accepting request"]);
                return;
            }

            if($data['remaining']) {
                $req->update(['requestID' => $reqID],['amount' => $data['remaining']]);
                $this->sendJson(["success"=> 1,'']);
            }
            else {
                $req->delete(['requestID' => $reqID]);
                $this->sendJson(["success"=> 1]);
            }
            $this->commitTransaction();

        }
        catch (\PDOException $e) {
            $this->rollbackTransaction();
            $this->sendJson(["status"=> 0,"error" => $e->getMessage()]);
            return;
        }

    }

    private function acceptedUserDetails(): array {
        $userType = $this->getUserType();
        $user = null;
        $data = null;
        if($userType === 'logistic') {
            $logistic = logisticModel::getModel(['employeeID' => $_SESSION['user']]);
            $user = ccModel::getModel(['ccID' => $logistic->ccID]);
            $data = ['user'=> $user , 'data' => ['acceptedBy' => $user->ccID,'longitude' => $user->longitude,'latitude' => $user->latitude]];
        }
        else {
            $user = donorModel::getModel(['donorID' => $_SESSION['user']]);
            $data = ['user'=> $user , 'data' => ['acceptedBy' => $user->donorID,'longitude' => $user->longitude,'latitude' => $user->latitude]];
        }
        return $data;
    }

    private function deliveryDetails(DbModel $user,doneeModel $donee): array
    {
        $data = [];
        if ($user instanceof donorModel)
            $data = array_merge($data, [
                'end' => $donee->doneeID,
                'start' => $user->donorID
            ]);
        else if ($user instanceof ccModel) {
            $data = array_merge($data, [
                'end' => $donee->doneeID,
                'start' => $user->ccID
            ]);
        }
        return $data;
    }

    private function subdeliveryDetails(DbModel $user,doneeModel $donee):array {
        $donorFlag = $user instanceof donorModel;
        $sameCC = $user->ccID === $donee->ccID;
        $data = [];

        if($donorFlag) {
            $cc = ccModel::getModel(['ccID' => $user->ccID]);
            if($sameCC) {
                $data = array_merge($data,[
                    'subdeliveryCount' => 2,
                    'deliveryStage' => 2,
                    'start' => $user->donorID,
                    'end' => $user->ccID,
                    'fromLongitude' => $user->longitude,
                    'fromLatitude' => $user->latitude,
                    'toLongitude' => $cc->longitude,
                    'toLatitude' => $cc->latitude
                ]);
            }
            else {
                $data = array_merge($data,[
                    'subdeliveryCount' => 3,
                    'deliveryStage' => 3,
                    'start' => $user->donorID,
                    'end' => $user->ccID,
                    'fromLongitude' => $user->longitude,
                    'fromLatitude' => $user->latitude,
                    'toLongitude' => $cc->longitude,
                    'toLatitude' => $cc->latitude
                ]);
            }
        }
        else {
            if($sameCC) {
                $data = array_merge($data,[
                    'subdeliveryCount' => 1,
                    'deliveryStage' => 1,
                    'start' => $user->ccID,
                    'end' => $donee->doneeID,
                    'fromLongitude' => $user->longitude,
                    'fromLatitude' => $user->latitude,
                    'toLongitude' => $donee->longitude,
                    'toLatitude' => $donee->latitude
                ]);
            }
            else {
                $cc = ccModel::getModel(['ccID' => $donee->ccID]);
                $data = array_merge($data,[
                    'subdeliveryCount' => 2,
                    'deliveryStage' => 2,
                    'start' => $user->ccID,
                    'end' => $donee->ccID,
                    'fromLongitude' => $user->longitude,
                    'fromLatitude' => $user->latitude,
                    'toLongitude' => $cc->longitude,
                    'toLatitude' => $cc->latitude
                ]);
            }
        }
        return $data;
    }

    protected function filterRequestsAdmin(Request $request,Response $response) {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];
        $search = $data['search'];

        try {
            $this->sendJson(["status"=> 1,"pendingRequests" => $this->getPendingRequestsAdmin($filters,$sort,$search), "acceptedRequests" => $this->getAcceptedRequestsAdmin($filters,$sort,$search)]);
        }
        catch (\PDOException $e) {
            $this->sendJson(["status"=> 0,"message" => $e->getMessage()]);
            return;
        }

    }

    private function getPendingRequestsAdmin($filters,$sort,$search) : array {
        $cols = "u.username,r.approval,r.postedDate,s.subcategoryName, CONCAT(r.amount,' ',s.scale) as amount,r.requestID";
        $sql = 'SELECT ' . $cols . ' FROM request r INNER JOIN users u ON r.postedBy = u.userID INNER JOIN subcategory s on r.item = s.subcategoryID';

        $where = " WHERE ";

        if(!empty($filters)) {
            $where .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
        }

        if(!empty($search)) {
            $where = $where === " WHERE " ? $where : $where . " AND ";
            $where .= " (username LIKE '%$search%' OR notes LIKE '%$search%' OR address LIKE '%$search%')";
        }

        $sql .= $where === " WHERE " ? "" : $where;

        if(!empty($sort['DESC'])) {
            $sql .= " ORDER BY " . implode(", ", $sort['DESC']) . " DESC";
        }

        $statement = requestModel::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);

    }

    private function getAcceptedRequestsAdmin($filters,$sort,$search) : array {
        $cols = "u.username,r.acceptedBy,r.postedDate,s.subcategoryName, CONCAT(r.amount,' ',s.scale) as amount, r.deliveryStatus,r.acceptedID";
        $sql = 'SELECT ' . $cols . ' FROM acceptedrequest r INNER JOIN users u ON r.postedBy = u.userID INNER JOIN subcategory s on r.item = s.subcategoryID';

        $where = " WHERE ";

        if(isset($filters['approval'])) {
            unset($filters['approval']);
        }

        if(!empty($filters)) {
            $where .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
        }

        if(!empty($search)) {
            $where = $where === " WHERE " ? $where : $where . " AND ";
            $where .= " (username LIKE '%$search%' OR notes LIKE '%$search%' OR address LIKE '%$search%')";
        }

        $sql .= $where === " WHERE " ? "" : $where;

        if(!empty($sort['DESC'])) {
            $sql .= " ORDER BY " . implode(", ", $sort['DESC']) . " DESC";
        }

        $statement = requestModel::prepare($sql);
        $statement->execute();
        $result =  $statement->fetchAll(\PDO::FETCH_ASSOC);
        $stmnt2 = requestModel::prepare("SELECT userID,username as acceptedBy FROM users WHERE userType = 'donor' UNION ALL SELECT ccID,CONCAT(city,' (CC)') as acceptedBY FROM communitycenter");
        $stmnt2->execute();
        $acceptedBy = $stmnt2->fetchAll(\PDO::FETCH_KEY_PAIR);

        foreach($result as &$row) {
            $row['acceptedBy'] = $acceptedBy[$row['acceptedBy']];
        }

        return $result;
    }

    protected function filterRequests(Request $request,Response $response) {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];

        try {

        $sql = 'Select * FROM request INNER JOIN subcategory s on request.item = s.subcategoryID INNER JOIN category c on s.categoryID = c.categoryID';
        $requests = requestModel::runCustomQuery($sql,$filters,$sort);

        if($_SESSION['userType'] === 'donor') {
            $filters['acceptedBy'] = $_SESSION['user'];
        }
        else {
            $logistic  = logisticModel::getModel(['employeeID' => $_SESSION['user']]);
            $filters['acceptedBy'] = $logistic->ccID;
        }

        $sql = 'Select * FROM acceptedrequest INNER JOIN subcategory s on acceptedrequest.item = s.subcategoryID INNER JOIN category c on s.categoryID = c.categoryID';

            $this->sendJson(['status' => 1,'requests' => $requests, 'acceptedRequests' => acceptedModel::runCustomQuery($sql,$filters,$sort)]);
        }
        catch (\PDOException $e) {
            $this->sendJson(["status"=> 0,"message" => $e->getMessage()]);
            return;
        }

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterOwnRequests(Request $request, Response $response) : void {

        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];

        $doneeID = $_SESSION['user'];

        $activeRequestFilters = ['r.postedBy' => $doneeID,'!r.approval' => 'Cancelled'];
        $acceptedRequestFilters = ['r.postedBy' => $doneeID, '!r.deliveryStatus' => 'Completed'];
        $completedRequestFilters = ['r.postedBy' => $doneeID, 'r.deliveryStatus' => 'Completed'];

        if(!empty($filters)) {
            $activeRequestFilters['r.item'] = $filters['item'];
            $acceptedRequestFilters['r.item'] = $filters['item'];
            $completedRequestFilters['r.item'] = $filters['item'];
        }

        $activeRequestSort = ['DESC' => []];
        $acceptedRequestSort = ['DESC' => []];
        $completedRequestSort = ['DESC' => []];

        if(!empty($sort['DESC'])) {
            foreach ($sort['DESC'] as $key => $value) {
                $activeRequestSort['DESC'][] = $key === 'postedDate' ? 'r.postedDate' : "r.amount";
                $acceptedRequestSort['DESC'][] = $key === 'postedDate' ? 'r.postedDate' : "r.amount";
                $completedRequestSort['DESC'][] = $key === 'postedDate' ? 'r.postedDate' : "SUM(r.amount)";
            }
        }

        try {
            $activeRequestSql = "SELECT r.*,subcategoryName,CONCAT(r.amount,' ',s.scale) AS amount FROM request r LEFT JOIN subcategory s ON r.item = s.subcategoryID";
            $acceptedRequestSql = "SELECT * FROM acceptedrequest r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID";
            $completedRequestSql = "SELECT *,CONCAT(SUM(r.amount),' ',s.scale) AS amount,COUNT(r.requestID) AS users FROM acceptedrequest r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID";

            $activeRequests = requestModel::runCustomQuery($activeRequestSql,$activeRequestFilters,$activeRequestSort,[],);

            $acceptedInfo = requestModel::runCustomQuery("SELECT a.requestID,CONCAT(COUNT(*),' users') AS users, 
                                        CONCAT(SUM(a.amount),' ',s.scale) AS amount FROM acceptedrequest a 
                                        INNER JOIN subcategory s ON a.item = s.subcategoryID ",
                                        ['a.postedBy' => $_SESSION['user']],[],[],'a.requestID');

            foreach ($activeRequests as &$request) {

                $index = array_search($request['requestID'], array_column($acceptedInfo,'requestID'));

                if($index !== false) {
                    $request['users'] = $acceptedInfo[$index]['users'];
                    $request['acceptedAmount'] = $acceptedInfo[$index]['amount'];
                }
                else {
                    $request['users'] = '0 ';
                    $request['acceptedAmount'] = 0;
                }
            }

            $this->sendJson(
                [
                    "status" => 1,
                    "activeRequests" => $activeRequests,
                    "acceptedRequests" => requestModel::runCustomQuery($acceptedRequestSql,$acceptedRequestFilters,$acceptedRequestSort),
                    "completedRequests" => requestModel::runCustomQuery($completedRequestSql,$completedRequestFilters,$completedRequestSort,[],'r.requestID'),
                ]
            );
        }
        catch (\PDOException $e) {
            $this->sendJson(["status"=> 0,"message" => $e->getMessage()]);
            return;
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterRequestsManager(Request $request, Response $response) : void {

        $data = $request->getJsonData();

        $filters = $data['filters'];
        $sort = $data['sort'];

        try {

            $manager = managerModel::getModel(['employeeID' => $_SESSION['user']]);

            $this->sendJson([
                'status' => 1,
                'requests' => requestModel::getRequestsUnderCCFilteredAndSorted($manager->ccID,$filters,$sort),
                'completedRequests' => acceptedModel::getCompletedReqeustsUnderCCFilteredAndSorted($manager->ccID,$filters,$sort)
            ]);

        }
        catch (\PDOException $e) {
            $this->sendJson(["status"=> 0,"message" => $e->getMessage()]);
            return;
        }

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function requestPopupManager(Request $request, Response $response) : void {

        $data = $request->getJsonData();

        $completed  = $data['completed'];

        unset($data['completed']);

        try {

            if( $completed ) {
                $this->requestPopupManagerCompleted($data);
            }
            else {
                $this->requestPopupManagerPosted($data);
            }

        }
        catch (\PDOException $e) {
            $this->sendJson(["status"=> 0,"message" => $e->getMessage()]);
            return;
        }

    }

    /**
     * @param $data
     * @return void
     */
    private function requestPopupManagerPosted($data) : void {

        $sql = "SELECT r.*,CONCAT(r.amount,' ',s.scale) AS amount,s.*,'category' AS categoryName,COUNT(a.acceptedBy) AS users,CONCAT(SUM(a.amount),' ',s.scale) AS acceptedAmount FROM request r LEFT JOIN subcategory s ON r.item = s.subcategoryID LEFT JOIN acceptedrequest a ON a.requestID = r.requestID";

        $request = requestModel::runCustomQuery($sql,['r.requestID' => $data['r.requestID']],[],[],'a.requestID');

        $this->sendJson([
            'status' => 1,
            'request' => $request[0]
        ]);

    }

    /**
     * @param $data
     * @return void
     */
    private function requestPopupManagerCompleted($data) : void {

        $sql = "SELECT *,CONCAT(SUM(r.amount),' ',s.scale) AS amount,COUNT(r.requestID) AS users,CONCAT(SUM(r.amount),' ',s.scale) AS acceptedAmount FROM acceptedrequest r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID";

        $request = acceptedModel::runCustomQuery($sql,['r.acceptedID' => $data['r.requestID']],[],[],'r.requestID');

        $this->sendJson([
            'status' => 1,
            'request' => $request[0]
        ]);

    }

    protected function cancelRequest(Request $request,Response $response) : void {

            $data = $request->getJsonData();

            $requestID = $data['requestID'];

            try {

                $this->startTransaction();

                $request = requestModel::getModel(['requestID' => $requestID]);

                if($request->postedBy !== $_SESSION['user']) {
                    $this->sendJson([
                        'status' => 0,
                        'message' => 'You are not allowed to cancel this request']);
                    return;
                }

                $request->update(
                    ['requestID' => $requestID],
                    ['approval' => 'Cancelled']);

                $this->commitTransaction();

                $this->sendJson([
                    'status' => 1,
                    'message' => 'Request cancelled successfully']);

            }
            catch (\PDOException $e) {
                $this->sendJson(['status' => 0,'message' => $e->getMessage()]);
            }

    }

}