<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\loginMiddleware;
use app\core\Request;
use app\core\Response;

class redirectController extends Controller
{
    public function __construct($func,Request $request,Response $response) {

        if(method_exists($this, $func)) {
            $this->$func($request,$response);
        } else {
            throw new \Exception('Method does not exist');
        }

    }

    public function redirectHome($request, $response)
    {
        switch ($this->getUserType()) {
            case 'admin':
                $this->render('/admin/home');
                break;
            case 'manager':
                $this->render('/manager/home');
                break;
            case 'logistic':
                $this->render('/logistic/home');
                break;
            case 'driver':
                $this->render('/driver/home');
                break;
            case 'cho':
                $this->render('/cho/home');
                break;
            case 'donee':
                $this->render('/donee/home');
                break;
            case 'donor':
                $this->render('/donor/home');
                break;
            default:
                $this->render('/guest/home');
                break;
        }
    }


}