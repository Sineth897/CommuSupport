<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\ccMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\ccModel;
use app\models\choModel;

class ccController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new ccMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewCC(Request $request,Response $response) {
        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new ccModel();
        $user = $this->getUserModel();
        $this->render($userType ."/CC/view","View Community Centers",[
            'model' => $model,
            'user' => $user
        ]);
    }


    protected function adminViewCC(Request $request, Response $response)
    {
        $model = new ccModel();
        $user = $this->getUserModel();
        $this->render("admin/CC/view","View Community Centers",[
            'model' => $model,
            'user' => $user
        ]);
    }

    protected function getCoordinates(Request $request,Response $response) {
        $model = new ccModel();
        $this->sendJson($model->getCoordinates());
    }

    protected function filterCC(Request $request,Response $response) {
        $data = $request->getJsonData();

        try {
            if(empty($data['cho'])) {
                $this->sendJson(['status' => 1, 'CCs' => ccModel::getAllData(), 'chos' => choModel::getCHOs()]);
                return;
            }
            $this->sendJson(['status' => 1, 'CCs' => ccModel::getAllData($data), 'chos' => choModel::getCHOs()]);
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

}