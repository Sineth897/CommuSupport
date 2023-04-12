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
                $response->redirect('/admin/communityheadoffices');
                break;
            case 'manager':
                $response->redirect('/manager/events');
                break;
            case 'logistic':
                $response->redirect('/logistic/deliveries');
                break;
            case 'driver':
                $response->redirect('/driver/deliveries');
                break;
            case 'cho':
                $response->redirect('/cho/communitycenters');
                break;
            case 'donee':
                $response->redirect('/donee/request');
                break;
            case 'donor':
                $response->redirect('/donor/donations');
                break;
            default:
                $this->render('/guest/home', 'Welcome to CommuSupport!');
                break;
        }
    }

    protected  function test(Request $request,Response $response) {

        $this->setFlash('success','Test Flash Message');
        $this->setFlash('error','Test Flash Message');

        $this->render('test/test','Test Page',[
            'request' => $request
        ]);
    }


}