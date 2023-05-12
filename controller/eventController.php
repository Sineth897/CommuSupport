<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\exceptions\forbiddenException;
use app\core\exceptions\methodNotFound;
use app\core\middlewares\eventMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\eventModel;
use app\models\managerModel;

class eventController extends Controller
{
    /**
     * @param string $func
     * @param Request $request
     * @param Response $response
     * @throws methodNotFound
     * @throws forbiddenException
     */
    public function __construct(string $func, Request $request, Response $response)
    {
        $this->middleware = new eventMiddleware();
        parent::__construct($func, $request, $response);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function viewEvents(Request $request, Response $response) : void {

        $this->checkLink($request);

        $userType = $this->getUserType();
        $model = new eventModel();

        $this->render($userType . "/event/view", "View Events", [
            'model' => $model
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function createEvent(Request $request, Response $response) : void {

        $this->checkLink($request);

        $model = new eventModel();

        if($request->isPost()) {
            $model->getData($request->getBody());
            if($model->validate($request->getBody()) && $model->save()) {
                $this->setFlash('success', 'Event created successfully');
                $model->reset();
            }
            else {
                $this->setFlash('error', 'Event creation failed');
            }
        }

        $this->render("manager/event/create", "Create a Event" ,[
            'model' => $model
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterEvents(Request $request, Response $response) : void {
        try {
            $model = new eventModel();
            $user = managerModel::getModel(['employeeID'=>Application::session()->get('user')]);
            $filters = $request->getJsonData()['filters'];
            $sortBy = $request->getJsonData()['sortBy'];
            if(empty($sortBy['DESC'])) {
                $sortBy = [];
            }
            $filters['ccID'] = $user->ccID;
            $events = $model->retrieve($filters,$sortBy);
            $categoryIcons = eventModel::getEventCategoryIcons();
            $this->sendJson([
                'status' => 1,
                'event' => $events,
                'icons' => $categoryIcons
            ]);
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterEventsUser(Request $request, Response $response) : void {

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

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function eventPopUp(Request $request, Response $response) : void {
        $model = new eventModel();
        $event = $model->retrieveWithJoin('eventcategory','eventCategoryID',$request->getJsonData())[0];
        $eventCategoryIcons = eventModel::getEventCategoryIcons();
        $this->sendJson([
            'event' => $event,
            'icons' => $eventCategoryIcons,
            'data' => $request->getJsonData()
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function eventPopUpUser(Request $request, Response $response) : void {
        $model = new eventModel();
        $event = $model->retrieveWithJoin('eventcategory','eventCategoryID',$request->getJsonData())[0];
        $eventCategoryIcons = eventModel::getEventCategoryIcons();
        $eventID = $request->getJsonData()['event.eventID'];
        $this->sendJson([
            'event' => $event,
            'icons' => $eventCategoryIcons,
            'data' => $request->getJsonData(),
            'isGoing' => $model->isGoing($eventID),
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected  function participate(Request $request, Response $response) : void {
        $model = new eventModel();
        $data = $request->getJsonData();
        $data = $data['eventID'];
        try {
            $this->startTransaction();
            eventModel::setParticipation($data);
            $this->commitTransaction();
            $this->sendJson([
                'status' => 1
            ]);
        }
        catch (\Exception $e) {
            $this->rollbackTransaction();
            $this->sendJson([
                'status' => 0,
                'error' => $e->getMessage(),
            ]);
        }
    }


    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function updateEvent(Request $request, Response $response) : void {
        $this->checkLink($request);

        $data = $request->getJsonData();
        $func = $data['do'];
        unset($data['do']);
        $data = $data['data'];
        try {
            $this->startTransaction();
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
            $this->commitTransaction();
        }
        catch (\Exception $e) {
            $this->rollbackTransaction();
            $this->sendJson([
                'status' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param $eventID
     * @param $data
     * @return void
     */
    private function updateFields($eventID, $data) : void {

        $model = new eventModel();
        unset($data['eventID']);

        $model->update(['eventID'=>$eventID],$data);

    }

    /**
     * @param $eventID
     * @return void
     */
    private function cancelEvent($eventID) : void {

        $model = new eventModel();

        $model->update(['eventID'=>$eventID],['status'=>'Cancelled']);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterEventsAdmin(Request $request, Response $response) : void {
        $data = $request->getJsonData();
        $filters = $data['filters'];
        $sort = $data['sortBy'];
        $search = $data['search'];

        $sql = "SELECT * FROM event";

        $where = " WHERE ";

        if(!empty($filters)) {
            $where .= implode(" AND ", array_map(fn($key) => "$key = '$filters[$key]'", array_keys($filters)));
        }

        if(!empty($search)) {
            $where = $where === " WHERE " ? $where : $where . " AND ";
            $where .= " (theme LIKE '%$search%' OR description LIKE '%$search%' OR organizedBy LIKE '%$search%' OR location LIKE '%$search%')";
        }

        $sql .= $where === " WHERE " ? "" : $where;

        if(!empty($sort['DESC'])) {
            $sql .= " ORDER BY " . implode(", ",$sort["DESC"]);
        }

        try {
            $statement = eventModel::prepare($sql);
            $statement->execute();
            $this->sendJson([
                'status' => 1,
                'events' => $statement->fetchAll(),
            ]);
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }
    }

}