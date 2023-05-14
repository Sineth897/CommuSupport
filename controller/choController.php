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

    // view users
    protected function viewUsers(Request $request, Response $response)
    {
        $this->checkLink($request);

        $model = new \app\models\choModel();
        $user = new \app\models\userModel();
        $this->render('cho/users/view', 'View Users',
            ['model' => $model,
                'user'=>$user,]);
    }


//    public function  filterCommunityCenters(Request $request, Response $response)
//    {
//        $data = $request->getJsonData();
//        $search = $data['search'];
//        $sql = "select * from communitycenter";
//        $where ="WHERE ";
//
//        if (!empty($search)){
//            $where = $where === " WHERE " ? $where : $where . " AND ";
//            $where .= " (city LIKE '%$search%')";
//        }
//
//        $sql .=$where === " WHERE " ? "" : $where;
//
//
//    }

    protected function viewIndividualCHO(Request $request, Response $response)
    {
        $this->checkLink($request);

        $model = new \app\models\choModel();
        $this->render('admin/communityheadoffices/view/IndividualCHO', 'View Cho', ['model' => $model,]);
    }



}