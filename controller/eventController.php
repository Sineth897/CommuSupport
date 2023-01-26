<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\eventMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\eventModel;

class eventController extends Controller
{
    public function __construct(string $func,Request $request,Response $response)
    {
        $this->middleware = new eventMiddleware();
        parent::__construct($func, $request, $response);

    }

    protected function viewEvents(Request $request,Response $response) {

        $user = $this->getUserType();
        $model = new eventModel();

        $this->render($user . "/events/view", "View Events", [
            'model' => $model
        ]);
    }

    protected function createEvent(Request $request,Response $response) {

        $model = new eventModel();

        if($request->isPost()) {
            $model->getData($request->getBody());
            if($model->validate($request->getBody()) && $model->save()) {
                $this->setFlash('result', 'Event created successfully');
                $model->reset();
            }
            else {
                $this->setFlash('result', 'Event creation failed');
            }
        }

        $this->render("manager/events/create", "Create a Event" ,[
            'model' => $model
        ]);

    }

    protected function filterEvents(Request $request,Response $response) {

        $model = new eventModel();
        $filters = $request->getJsonData();
        $events = $model->retrieve($filters);
        $categoryIcons = eventModel::getEventCategoryIcons();
        $this->sendJson(['events' => $events, 'icons' => $categoryIcons]);

    }

}