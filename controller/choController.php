<?php

namespace app\controller;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\ccModel;

class choController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new \app\core\middlewares\choMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewCho(Request $request, Response $response)
    {
        $this->checkLink($request);

        $model = new \app\models\choModel();
        $this->render('admin/communityheadoffices/view', 'View Cho', ['model' => $model,]);
    }

    protected function choPopup(Request $request, Response $response)
    {
        $data = $request->getJsonData();
        try {
            $model = new \app\models\choModel();
            $cho = $model->retrieveWithJoin('users','userID',$data,[],'choID')[0];
            $cc = ccModel::getAllData(['cho'=>$data['choID']]);
            $this->sendJson(['status'=> 1, 'cho'=>$cho,'communityCenters'=>$cc]);
        }
        catch (\Exception $e) {
            $this->sendJson(['status'=> 0,'error'=>$e->getMessage()]);
        }
    }

}