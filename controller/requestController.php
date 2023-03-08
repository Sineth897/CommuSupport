<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\requestMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\doneeModel;
use app\models\requestModel;

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
        $user = $this->getUserModel();
        $this->render($userType ."/request/view","View Requests",[
            'model' => $model,
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
        $acceptedAmount = $data['acceptedAmount'];
        $amount = $data['amount'];
        $req = requestModel::getModel(['requestID' => $reqID]);

        try{
            $this->startTransaction();
            $req->amount = $acceptedAmount;
            $result = $req->accept();

            if(!$result) {
                $this->rollbackTransaction();
                $this->sendJson(["success"=> 0,"error" => "Error accepting request"]);
                return;
            }

            if($acceptedAmount < $amount) {
                $req->update(['requestID' => $reqID],['amount' => $amount - $acceptedAmount]);
            }
            else {
                $req->delete(['requestID' => $reqID]);
            }
            $this->commitTransaction();
        }
        catch (\PDOException $e) {
            $this->rollbackTransaction();
            $this->sendJson(["status"=> 0,"error" => $e->getMessage()]);
        }

        $this->sendJson(["success"=> 1]);
    }

}