<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\doneeMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\ccModel;
use app\models\doneeModel;

class doneeController extends Controller
{

    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new doneeMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewDonees(Request $request, Response $response)
    {
        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new doneeModel();
        $user = $this->getUserModel();
        $this->render($userType . "/donees/view", "View Donees",[
            'model' => $model,
            'user' => $user
        ]);
    }

    protected function getData(Request $request, Response $response)
    {
        try {
            $data = $request->getJsonData();
            $this->sendJson($this->getDoneeDetails($data['doneeID'])[0]);
        } catch (\Exception $e) {
            $this->sendJson($e->getMessage());
        }

    }

    private function getDoneeDetails($doneeID) : array
    {
        $donee = doneeModel::getModel(['doneeID' =>$doneeID]);
        if($donee->type == "Individual") {
            return $donee->retrieveWithJoin('doneeindividual','doneeID',['donee.doneeID' => $doneeID]);
        } else {
            return $donee->retrieveWithJoin('doneeorganization','doneeID',['donee.doneeID' => $doneeID]);
        }
    }

    protected function verifyDonee(Request $request, Response $response)
    {
        try {
            $data = $request->getJsonData();
            $donee = doneeModel::getModel(['doneeID' => $data['doneeID']]);
            $donee->update(['doneeID' => $data['doneeID']],['verificationStatus' => 1]);
            $this->sendJson(['status' => 1]);
        } catch (\Exception $e) {
            $this->sendJson(['status' => 0,'message' => $e->getMessage()]);
        }
    }

    protected function doneesFilterAdmin(Request $request, Response $response)
    {
        try {
            $data = $request->getJsonData();
            $filters = $data['filters'];
            $sort = $data['sortBy'];
            $search = $data['search'];

            $sql  = "SELECT * FROM donee INNER JOIN users ON donee.doneeID = users.userID";
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

            $stmt = doneeModel::prepare($sql);
            $stmt->execute();
            $this->sendJson(['status'=> 1,'donees'=>$stmt->fetchAll(\PDO::FETCH_ASSOC), 'CCs' => ccModel::getCCs()]);
        } catch (\Exception $e) {
            $this->sendJson($e->getMessage());
        }
    }

    protected function filterDonees(Request $request,Response $response) {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sort'];
        $search = $data['search'];

        $user = $this->getUserModel();
        $user = $user->findOne(['employeeID' => $_SESSION['user']]);
        $filters['ccID'] = $user->ccID;

        $sql1  = "SELECT * FROM donee INNER JOIN doneeindividual d on donee.doneeID = d.doneeID";
        $sql2  = "SELECT * FROM donee INNER JOIN doneeorganization d on donee.doneeID = d.doneeID";
        $where1 = " WHERE ";
        $where2 = " WHERE ";

        if(!empty($filters)) {
            $where1 .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
            $where2 .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
        }

        if(!empty($search)) {
            $where1 = $where1 === " WHERE " ? $where1 : $where1 . " AND ";
            $where2 = $where2 === " WHERE " ? $where2 : $where2 . " AND ";
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
            $stmt1 = doneeModel::prepare($sql1);
            $stmt1->execute();
            $stmt2 = doneeModel::prepare($sql2);
            $stmt2->execute();
            $this->sendJson(['status'=> 1,'individualDonees'=>$stmt1->fetchAll(\PDO::FETCH_ASSOC), 'organizationDonees' => $stmt2->fetchAll(\PDO::FETCH_ASSOC)]);
        } catch (\Exception $e) {
            $this->sendJson(['status' => 0, "msg" => $e->getMessage(), "sql" => $sql1, "sql2" => $sql2]);
        }
    }

}