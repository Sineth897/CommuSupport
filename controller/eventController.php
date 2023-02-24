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

        $userType = $this->getUserType();
        $model = new eventModel();

        $this->render($userType . "/event/view", "View Events", [
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

        $this->render("manager/event/create", "Create a Event" ,[
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
            'event' => $events,
            'icons' => $categoryIcons
        ]);
    }

    protected function filterEventsUser(Request $request,Response $response) {

        $model = new eventModel();
        $filters = $request->getJsonData()['filters'];
        $sortBy = $request->getJsonData()['sortBy'];
        $events = $model->retrieve($filters,$sortBy);
        $categoryIcons = eventModel::getEventCategoryIcons();
        $this->sendJson([
            'event' => $events,
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

    protected function eventPopUpUser(Request $request,Response $response) {
        $model = new eventModel();
        $event = $model->retrieveWithJoin('eventCategory','eventCategoryID',$request->getJsonData())[0];
        $eventCategoryIcons = eventModel::getEventCategoryIcons();
        $eventID = $request->getJsonData()['event.eventID'];
        $this->sendJson([
            'event' => $event,
            'icons' => $eventCategoryIcons,
            'data' => $request->getJsonData(),
            'isGoing' => $model->isGoing($eventID),
            'test' => $eventID,
        ]);
    }

    protected  function participate(Request $request,Response $response) {
        $model = new eventModel();
        $data = $request->getJsonData();
        $data = $data['eventID'];
        try {
            eventModel::setParticipation($data);
            $this->sendJson([
                'status' => 1
            ]);
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function updateEvent(Request $request,Response $response) {
        $data = $request->getJsonData();
        $func = $data['do'];
        unset($data['do']);
        $data = $data['data'];
        try {
            switch ($func) {
                case 'update':
                    $this->updateFields($data['eventID'],$data);
                    break;
                case 'cancel':
                    $this->cancelEvent($data);
                    break;
                default:
                    throw new \Exception("Invalid function");
            }
            $this->sendJson([
                'status' => 1
            ]);
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function updateFields($eventID,$data) {
        $model = new eventModel();
        unset($data['eventID']);
        $model->update(['eventID'=>$eventID],$data);
    }

    private function cancelEvent($eventID) {
        $model = new eventModel();
        $model->update(['eventID'=>$eventID],['status'=>'Cancelled']);
    }

}