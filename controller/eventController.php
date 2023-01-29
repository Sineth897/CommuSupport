<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\eventMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\eventModel;
use app\models\managerModel;

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
        $user = managerModel::getModel(['employeeID'=>Application::session()->get('user')]);
        $filters = $request->getJsonData();
        $filters['ccID'] = $user->ccID;
        $events = $model->retrieve($filters);
        $categoryIcons = eventModel::getEventCategoryIcons();
        $this->sendJson([
            'events' => $events,
            'icons' => $categoryIcons
        ]);
    }

    protected function eventPopUp(Request $request,Response $response) {
        $model = new eventModel();
        $event = $model->retrieveWithJoin('eventCategory','eventCategoryID',$request->getJsonData())[0];
        $eventCategoryIcons = eventModel::getEventCategoryIcons();
        $this->sendJson([
            'event' => $event,
            'icons' => $eventCategoryIcons,
            'data' => $request->getJsonData()
        ]);
    }

}