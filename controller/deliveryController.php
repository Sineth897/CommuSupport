<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\deliveryMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\deliveryModel;

class deliveryController extends Controller
{
    public function __construct($func,Request $request,Response $response) {

        $this->middleware = new deliveryMiddleware();
        parent::__construct($func, $request, $response);

    }

    protected function viewDeliveries($request, $response)
    {
        $this->checkLink($request);

        $userType = $this->getUserType();
        $deliveries = new deliveryModel();
        $user = $this->getUserModel();

        $this->render($userType . '/deliveries/view', 'Deliveries', [
            'deliveries' => $deliveries,
            'user' => $user,
        ]);
    }

    protected function createDelivery(Request $request,Response $response) {

        $delivery = new deliveryModel();

        $this->render('/logistic/deliveries/create','Create a Delivery',[
                'deliveries' => $delivery
        ]);
    }
}