<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\doneeMiddleware;
use app\core\Request;
use app\core\Response;
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

    private function getDoneeDetails($doneeID)
    {
        $donee = doneeModel::getModel(['doneeID' =>$doneeID]);
        if($donee->type == "Individual") {
            return $donee->retrieveWithJoin('doneeindividual','doneeID',['donee.doneeID' => $doneeID]);
        } else {
            return $donee->retrieveWithJoin('doneeorganization','doneeID',['donee.doneeID' => $doneeID]);
        }
    }

}