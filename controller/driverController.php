<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\driverMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\ccModel;
use app\models\driverModel;

class driverController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new driverMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewDrivers(Request $request, Response $response)
    {

        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new driverModel();
        $user = $this->getUserModel();

        $this->render($userType . "/drivers/view", "View Drivers", [
            'model' => $model,
            'user' => $user,
        ]);
    }

    protected  function filterDriversAdmin(Request $request, Response $response)
    {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sortBy'];
        $search = $data['search'];

        $sql  = "SELECT * FROM driver";
        $where = " WHERE ";

        if(!empty($filters)) {
            $where .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
        }

        if(!empty($search)) {
            $where = $where === " WHERE " ? $where : $where . " AND ";
            $where .= " (name LIKE '%$search%' OR contactNumber LIKE '%$search%' OR licenseNo LIKE '%$search%' OR vehicleNo LIKE '%$search%')";
        }

        $sql .= $where === " WHERE " ? "" : $where;

        if(!empty($sort['DESC'])) {
            $sql .= " ORDER BY age";
        }

        try {
            $stmt = driverModel::prepare($sql);
            $stmt->execute();
            $this->sendJson(['status'=> 1,'drivers'=>$stmt->fetchAll(\PDO::FETCH_ASSOC), 'CCs' => ccModel::getCCs()]);
        } catch (\PDOException $e) {
            $this->sendJson(['status'=> 0,'msg'=>$e->getMessage()]);
        }

    }

    protected  function filterDrivers(Request $request, Response $response)
    {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sortBy'];
        $search = $data['search'];

        $user = $this->getUserModel();
        $user = $user->findOne(['employeeID' => $_SESSION['user']]);
        $filters['ccID'] = $user->ccID;

        $sql  = "SELECT * FROM driver";
        $where = " WHERE ";

        if(!empty($filters)) {
            $where .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
        }

        if(!empty($search)) {
            $where = $where === " WHERE " ? $where : $where . " AND ";
            $where .= " (name LIKE '%$search%' OR contactNumber LIKE '%$search%' OR licenseNo LIKE '%$search%' OR vehicleNo LIKE '%$search%')";
        }

        $sql .= $where === " WHERE " ? "" : $where;

        if(!empty($sort['DESC'])) {
            $sql .= " ORDER BY age";
        }

        try {
            $stmt = driverModel::prepare($sql);
            $stmt->execute();
            $this->sendJson(['status'=> 1,'drivers'=>$stmt->fetchAll(\PDO::FETCH_ASSOC)]);
        } catch (\PDOException $e) {
            $this->sendJson(['status'=> 0,'msg'=>$e->getMessage()]);
        }

    }

    protected function driverPopup(Request $request, Response $response)
    {
        $employeeID = $request->getJsonData()['employeeID'];

        try {
            $this->sendJson(['status'=> 1,'data'=>driverModel::getDriverDetails($employeeID)]);
        }
        catch (\PDOException $e) {
            $this->sendJson(['status'=> 0,'msg'=>$e->getMessage()]);
        }
    }

   

}

