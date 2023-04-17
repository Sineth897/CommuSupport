<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\donorMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\ccModel;
use app\models\donorModel;

class donorController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new donorMiddleware();
        parent::__construct($func, $request, $response);
    }


    protected function viewDonors(Request $request, Response $response)
    {
        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new donorModel();
        $user = $this->getUserModel();
        $this->render($userType . "/donors/view", "View Donors", [
            'model' => $model,
            'user' => $user
        ]);
    }

    protected function donorsFilterAdmin(Request $request, Response $response)
    {
        $data = $request->getJsonData();

        $filters = $data['filters'];
        $sort = $data['sortBy'];
        $search = $data['search'];

        $sql  = "SELECT * FROM donor INNER JOIN users ON donor.donorID = users.userID";
        $where = " WHERE ";

        if(!empty($filters)) {
            $where .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
        }

        if(!empty($search)) {
            $where = $where === " WHERE " ? $where : $where . " AND ";
            $where .= " (email LIKE '%$search%' OR contactNumber LIKE '%$search%' OR username LIKE '%$search%' OR address LIKE '%$search%')";
        }

        $sql .= $where === " WHERE " ? "" : $where;

        if(!empty($sort['DESC'])) {
            $sql .= " ORDER BY age";
        }

        try {
            $statement = donorModel::prepare($sql);
            $statement->execute();
            $this->sendJson(['status' => 1, 'donors' => $statement->fetchAll(\PDO::FETCH_ASSOC), 'CCs' => ccModel::getCCs()]);
        } catch (\Exception $e) {
            $this->sendJson(['status' => 0 , 'message' => $e->getMessage()]);
        }
    }

    protected function filterDonors(Request $request,Response $response) {

        $data = $request->getJsonData();

        $sort = $data['sort'];
        $search = $data['search'];

        $user = $this->getUserModel();
        $user = $user->findOne(['employeeID' => $_SESSION['user']]);

        $sql1  = "SELECT * FROM donor INNER JOIN donorindividual d on donor.donorID = d.donorID";
        $sql2  = "SELECT * FROM donor INNER JOIN donororganization d on donor.donorID = d.donorID";
        $where1 = " WHERE ccID = '$user->ccID' AND ";
        $where2 = " WHERE ccID = '$user->ccID' AND ";


        if(!empty($search)) {
            $where1 .= " (fname LIKE '%$search%' OR lname LIKE '%$search%' OR email LIKE '%$search%')";
            $where2 .= " (email LIKE '%$search%' OR organizationName LIKE '%$search%' OR representative LIKE '%$search%')";
        }

        $sql1 .= $where1 === " WHERE " ? "" : $where1;
        $sql2 .= $where2 === " WHERE " ? "" : $where2;

        if(!empty($sort['DESC'])) {
            $sql1 .= " ORDER BY registeredDate DESC";
            $sql2 .= " ORDER BY registeredDate DESC";
        }

        try {
            $statement1 = donorModel::prepare($sql1);
            $statement1->execute();
            $statement2 = donorModel::prepare($sql2);
            $statement2->execute();
            $this->sendJson(['status' => 1, 'individualDonors' => $statement1->fetchAll(\PDO::FETCH_ASSOC), 'organizationDonors' => $statement2->fetchAll(\PDO::FETCH_ASSOC)]);
        } catch (\Exception $e) {
            $this->sendJson(['status' => 0 , 'message' => $e->getMessage()]);
        }

    }
}
