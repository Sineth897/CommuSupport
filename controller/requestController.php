<?php

namespace app\controller;

use app\core\Controller;
use app\core\DbModel;
use app\core\middlewares\requestMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\acceptedModel;
use app\models\ccModel;
use app\models\deliveryModel;
use app\models\doneeModel;
use app\models\donorModel;
use app\models\logisticModel;
use app\models\requestModel;
use app\models\subdeliveryModel;

class requestController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new requestMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewRequests(Request $request,Response $response) {

        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new requestModel();
        $accepted = new acceptedModel();
        $user = $this->getUserModel();
        $this->render($userType ."/request/view","View Requests",[
            'model' => $model,
            'accepted' => $accepted,
            'user' => $user
        ]);
    }

    protected function postRequest(Request $request,Response $response) {

        $this->checkLink($request);

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

    protected function requestPopup(Request $request,Response $response) {
        $where = $request->getJsonData();
        $where = array_key_first($where) . " = '" . $where[array_key_first($where)] . "'";
        try {
            $sql = "SELECT * FROM request r INNER JOIN subcategory s ON r.item = s.subcategoryID INNER JOIN category c ON s.categoryID = c.categoryID WHERE $where";
            $stmnt = requestModel::prepare($sql);
            $stmnt->execute();
            $requestDetails = $stmnt->fetch(\PDO::FETCH_ORI_FIRST);

            $donee = doneeModel::getModel(['doneeID' => $requestDetails['postedBy']]);

            if($donee->type === 'Individual') {
                $donee = $donee->retrieveWithJoin('doneeIndividual','doneeID',['donee.doneeID' => $donee->doneeID]);
                $donee = $donee[0];
                $donee['name'] = $donee['fname'] . " " . $donee['lname'];
            }
            else {
                $donee = $donee->retrieveWithJoin('doneeOrganization','doneeID',['donee.doneeID' => $donee->doneeID]);
            };

            $this->sendJson([
                'requestDetails' => $requestDetails,
                'donee' => $donee
            ]);
        }
        catch (\PDOException $e) {
            $this->sendJson($e->getMessage() . $where);
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
            $this->sendJson($e->getMessage());
        }
    }

    private function approveRequest($data) {
        $requestmodel = new requestModel();
        $requestmodel->update($data,['approval' => 'Approved','approvedDate' => date('Y-m-d')]);
    }

    private function rejectRequest($data) {
        $requestID = $data['requestID'];
        $req = requestModel::getModel(['requestID' => $requestID]);
        $req->rejectRequest($data['rejectedReason']);
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

            if(!$result) {
                $this->rollbackTransaction();
                $this->sendJson(["success"=> 0,"error" => "Error accepting request"]);
                return;
            }

            if($data['remaining']) {
                $req->update(['requestID' => $reqID],['amount' => $data['remaining']]);
            }
            else {
                $req->delete(['requestID' => $reqID]);
            }
            $this->commitTransaction();
        }
        catch (\PDOException $e) {
            $this->rollbackTransaction();
            $this->sendJson(["status"=> 0,"error" => $e->getMessage()]);
            return;
        }

        $this->sendJson(["success"=> 1]);
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

}