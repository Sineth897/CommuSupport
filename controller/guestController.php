<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\guestMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\loginForm;
use app\models\regModel;

class guestController extends Controller
{

    public function __construct(string $func, Request $request, Response $response)
    {
        $this->setUserType();

        $this->middleware = new guestMiddleware();
        $this->middleware->execute($func, $this->userType);


        if(method_exists($this, $func)) {
            $this->$func($request, $response);
        } else {
            throw new \Exception('Method does not exist');
        }

    }

    public function home()
    {
        $params = [
            'name' => "Guest"
        ];
        $this->render("guest/home", $params);
    }

    public function form()
    {
//        $this->haveAccess(['managerModel']);
        $model = new regModel();
        $this->render("guest/form", [
            'model' => $model
        ]);
    }

    public function handleForm(Request $request)
    {
        $body = $request->getBody();

        $model = new regModel();
        $model->getData($body);

        if ($model->validate($body) && $model->save()) {
            Application::$app->session->setFlash('success', 'User registered successfully');
            Application::$app->response->redirect('/');
        }

        $this->render("guest/form", [
            'model' => $model
        ]);
    }

    public function login(Request $request, Response $response)
    {
        $loginForm = new loginForm();
        if( $request->isPost() ) {
            $loginForm->getData($request->getBody());
            if( $loginForm->validate($request->getBody()) && $loginForm->login() ) {
                $response->redirect('/');
                return null;
            }
        }
        $this->render('guest/login', [
            'model' => $loginForm
        ]);
    }


}
