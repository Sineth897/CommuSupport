<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\DbModel;
use app\core\middlewares\inventoryMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\inventoryModel;
use app\models\logisticModel;

class inventoryController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new inventoryMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewInventory(Request $request, Response $response) {

        $userType = $this->getUserType();
        $inventory = new inventoryModel();
        $user = $this->getUserModel();
        $this->render($userType . "/inventory/view", "Inventory",[
            'inventory' => $inventory,
            'user' => $user
        ]);
    }

    protected function addInventory(Request $request, Response $response) {
        $inventory = new inventoryModel();
        $data = ($request->getJsonData())['data'];

        $inventory->getData($data);
        if($inventory->validate($data) && $inventory->save()) {
            $this->sendJson(['success' => 1]);
            $inventory->reset();
        }
        else {
            $this->sendJson(['success' => 0]);
        }
    }

    protected function filterInventory(Request $request, Response $response) {
        $inventory = new inventoryModel();

        $logistic = logisticModel::getModel(['employeeID' =>Application::session()->get('user')]);

        $filters = ($request->getJsonData())['filters'];
        $filter['ccID'] = $logistic->ccID;

        $this->sendJson($inventory->retrieveWithJoin('subcategory', 'subcategoryID', $filters));
    }

}