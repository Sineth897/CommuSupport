<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\DbModel;
use app\core\middlewares\inventoryMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\inventorylog;
use app\models\inventoryModel;
use app\models\logisticModel;
use app\models\userModel;

class inventoryController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new inventoryMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewInventory(Request $request, Response $response) {

        $this->checkLink($request);

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
         try {
             $this->startTransaction();
             if($inventory->validate($data) && $inventory->save()) {
                 inventorylog::logLogisticAddingInventoryManually($data['subcategoryID'],$data['amount'],$data['remark']);
                 $this->sendJson(['success' => 1]);
                 $inventory->reset();
                 $this->commitTransaction();
             }
             else {
                 $this->sendJson(['success' => 0]);
                 $this->rollbackTransaction();
             }
         }
            catch (\Exception $e) {
                $this->rollbackTransaction();
                $this->sendJson(['success' => 0, 'error' => $e->getMessage()]);
            }

    }

    protected function filterInventory(Request $request, Response $response) {
        $inventory = new inventoryModel();

        $logistic = logisticModel::getModel(['employeeID' =>Application::session()->get('user')]);

        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sortBy = $data['sortBy'];
        if(empty($sortBy['DESC'])) {
            $sortBy = [];
        }
        $filters['ccID'] = $logistic->ccID;

        try {
            $this->sendJson($inventory->retrieveWithJoin('subcategory', 'subcategoryID', $filters, $sortBy));
        }
        catch (\Exception $e) {
            $this->sendJson(['success' => 0, 'error' => $e->getMessage()]);
        }
    }

    protected function getCurrentInventory(Request $request,Response $response) {
        $logistic = logisticModel::getModel(['employeeID' => Application::session()->get('user')]);

        $sql = "SELECT s.subcategoryName,CONCAT(i.amount,' ',s.scale) as stock FROM inventory i INNER JOIN subcategory s ON i.subcategoryID = s.subcategoryID";

        try {
            $this->sendJson(['status' => 1, 'inventory' => inventoryModel::runCustomQuery($sql,['ccID' => $logistic->ccID],[],[],\PDO::FETCH_KEY_PAIR)]);
        }
        catch (\Exception $e) {
            $this->sendJson(['status' => 0, 'error' => $e->getMessage()]);
        }
    }

}