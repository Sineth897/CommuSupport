<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\loginMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\eventModel;

class redirectController extends Controller
{
    public function __construct($func,Request $request,Response $response) {

        if(method_exists($this, $func)) {
            $this->$func($request,$response);
        } else {
            throw new \Exception('Method does not exist');
        }

    }

    protected function redirectHome($request, $response)
    {
        switch ($this->getUserType()) {
            case 'admin':
                $response->redirect('/admin/communitycenters');
                break;
            case 'manager':
                $response->redirect('/manager/events');
                break;
            case 'logistic':
                $response->redirec('/logistic/deliveries');
                break;
            case 'driver':
                $response->redirec('/driver/deliveries');
                break;
            case 'cho':
                $response->redirec('/cho/communitycenters');
                break;
            case 'donee':
                $response->redirec('/donee/request');
                break;
            case 'donor':
                $response->redirec('/donor/donations');
                break;
            default:
                $this->render('/guest/home');
                break;
        }
    }


}