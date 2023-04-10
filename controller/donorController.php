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

    protected function donorsFilter(Request $request, Response $response)
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
}
