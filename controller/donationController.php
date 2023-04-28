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
        $data = array_merge($data,$this->donationDetails($donor,$cc),
            ['deliveryID' => $deliveryID],
            $this->deliveryDetails($donor,$cc),
            $this->subdeliveryDetails($donor,$cc));
        //loading data to models
        $model->getData($data);
        $delivery->getData($data);
        $subdelivery->getData($data);

        try {
            $this->startTransaction();
            $delivery->save();
            $model->save();
            $subdelivery->save();
            $this->commitTransaction();
            $this->sendJson(['status' => 1 , 'msg' => 'Donation created successfully']);
        }
        catch (\Exception $e) {
            $this->rollbackTransaction();
            $this->sendJson((['status' => 0 , 'msg' => $e->getMessage()]));
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

    protected function filterDonationsAdmin(Request $request,Response $response) {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sortBy'];
        $search = $data['search'];

        $cols = 'd.donationID,u.username,CONCAT(d.amount," ",s.scale) AS amount,d.date,s.subcategoryName,d.donateTo,d.deliveryStatus,c.city';
        $sql = "SELECT " . $cols . " FROM donation d LEFT JOIN users u ON d.createdBy = u.userID LEFT JOIN subcategory s ON d.item = s.subcategoryID LEFT JOIN communitycenter c ON d.donateTo = c.ccID";

        $where = " WHERE ";

        if(!empty($filters)) {
            $where .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
        }

        if(!empty($search)) {
            $where = $where === " WHERE " ? $where : $where . " AND ";
            $where .= " (username LIKE '%$search%')";
        }

        $sql .= $where === " WHERE " ? "" : $where;

        if(!empty($sort['DESC'])) {
            $sql .= " ORDER BY age";
        }

        try {
            $statement = donationModel::prepare($sql);
            $statement->execute();
            $this->sendJson(['status' => 1, 'donations' => $statement->fetchAll(\PDO::FETCH_ASSOC)]);
        }
        catch (\Exception $e) {
            $this->sendJson(['status' => 0 , 'msg' => $e->getMessage()]);
        }

    }

    protected function filterDonationsEmployee(Request $request,Response $response) {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];

        $user = $this->getUserModel();
        $user = $user->findOne(['employeeID' => $_SESSION['user']]);

        $filters = array_merge($filters,['d.donateTo' => $user->ccID]);

        $sql = "SELECT *,CONCAT(d.amount,' ',s.scale) AS amount FROM donation d INNER JOIN users u on d.createdBy = u.userID INNER JOIN subcategory s ON s.subcategoryID = d.item ";

        try {
            $this->sendJson(['status' => 1, 'donations' => donationModel::runCustomQuery($sql,$filters,$sort),'filters' => $filters]);
        }
        catch (\Exception $e) {
            $this->sendJson(['status' => 0 , 'msg' => $e->getMessage()]);
        }


    }

    protected function filterDonations(Request $request,Response $response)
    {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];

        $user = $this->getUserModel();
        $user = $user->findOne(['donorID' => $_SESSION['user']]);

        $filters = array_merge($filters, ['d.createdBy' => $user->donorID]);

        $sql = "SELECT *,CONCAT(d.amount,' ',s.scale) AS amount FROM donation d INNER JOIN communitycenter c on d.donateTo = c.ccID INNER JOIN subcategory s ON s.subcategoryID = d.item ";

        try {
            $this->sendJson(['status' => 1, 'donations' => donationModel::runCustomQuery($sql, $filters, $sort), 'filters' => $filters]);
        } catch (\Exception $e) {
            $this->sendJson(['status' => 0, 'msg' => $e->getMessage()]);
        }
    }

    protected function donationPopupDonor(Request $request,Response $response) {
        $data = $request->getJsonData();
        $donation = "SELECT *,CONCAT(d.amount,' ',s.scale) AS amount,CONCAT(c.city,' CC') AS city FROM donation d INNER JOIN subcategory s ON d.item = s.subcategoryID INNER JOIN communitycenter c ON d.donateTo = c.ccID";
        $delivery = "SELECT s.*,d.*,s.status AS deliveryStatus FROM subdelivery s LEFT JOIN delivery d ON s.deliveryID = d.deliveryID LEFT JOIN donation don ON s.deliveryID = don.deliveryID";

        try {
            $this->sendJson(['status' => 1, 'donation' => donationModel::runCustomQuery($donation,['d.donationID' => $data['donationID']])[0],'deliveries' => subdeliveryModel::runCustomQuery($delivery,['don.donationID' => $data['donationID']])]);
        }
        catch (\Exception $e) {
            $this->sendJson(['status' => 0 , 'msg' => $e->getMessage()]);
        }
    }

    protected function donationPopupEmployee(Request $request,Response $response) {
        $data = $request->getJsonData();
        $donation = "SELECT *,CONCAT(d.amount,' ',s.scale) AS amount FROM donation d INNER JOIN subcategory s ON d.item = s.subcategoryID INNER JOIN users u ON d.createdBy = u.userID";
        $delivery = "SELECT s.*,d.*,s.status AS deliveryStatus FROM subdelivery s LEFT JOIN delivery d ON s.deliveryID = d.deliveryID LEFT JOIN donation don ON s.deliveryID = don.deliveryID";

        try {
            $this->sendJson(['status' => 1, 'donation' => donationModel::runCustomQuery($donation,['d.donationID' => $data['donationID']])[0],'deliveries' => subdeliveryModel::runCustomQuery($delivery,['don.donationID' => $data['donationID']])]);
        }
        catch (\Exception $e) {
            $this->sendJson(['status' => 0 , 'msg' => $e->getMessage()]);
        }
    }

   
}