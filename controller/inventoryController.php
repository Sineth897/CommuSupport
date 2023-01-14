<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\inventoryMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\inventoryModel;

class inventoryController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new inventoryMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewInventory() {

        $userType = $this->getUserType();
        $inventory = new inventoryModel();
        $user = $this->getUserModel();
        $this->render($userType . "/inventory/view", "Inventory",[
            'inventory' => $inventory,
            'user' => $user
        ]);
    }

}