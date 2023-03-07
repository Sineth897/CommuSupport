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
        $this->checkLink($request);

        $model = new \app\models\choModel();
        $this->render('admin/communityheadoffices/view', 'View Cho', ['model' => $model,]);
    }

}