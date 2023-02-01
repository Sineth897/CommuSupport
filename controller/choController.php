<?php

namespace app\controller;

use app\core\Controller;
use app\core\Request;
use app\core\Response;

class choController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new \app\core\middlewares\choMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewCho(Request $request, Response $response)
    {
        $model = new \app\models\choModel();
        $this->render('admin/communityheadoffices/view', 'View Cho', ['model' => $model,]);
    }

    protected function viewManager(Request $request,Response $response)
    {
        $model= new \app\models\managerModel();
        $this->render('manager/view','View Managers',[
            'model'=>$model,

        ]);
    }

    protected function viewLogistic(Request $request,Response $response)
    {
        $model= new \app\models\logisticModel();
        $this->render('logistic/view','View Logistic Managers',[
            'model'=>$model,

        ]);
    }
}