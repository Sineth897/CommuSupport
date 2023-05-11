<?php

namespace app\controller;

use app\core\Controller;
use app\core\exceptions\forbiddenException;
use app\core\exceptions\methodNotFound;
use app\core\middlewares\adminMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\categoryModel;
use app\models\requestModel;
use app\models\subcategoryModel;

class adminController extends Controller
{

    /**
     * @param string $func
     * @param Request $request
     * @param Response $response
     * @throws forbiddenException
     * @throws methodNotFound
     */
    public function __construct(string $func, Request $request, Response $response)
    {

        $this->middleware = new adminMiddleware();
        parent::__construct($func, $request, $response);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function addCategory(Request $request, Response $response) : void {

        $category = new categoryModel();

        if(!$category->validate($request->getJsonData())) {
            $response->setStatusCode(400);
            $this->sendJson(['status' => 0,'message' => $category->getFirstError('categoryName')]);
            return;
        }

        $category->getData($request->getJsonData());

        if(!$category->save()) {
            $response->setStatusCode(500);
            $this->sendJson(['status' => 0,'message' => 'Something went wrong']);
            return;
        }

        $this->sendJson(['status' => 1,'message' => 'Category added successfully']);

    }

    protected function getCategories(Request $request, Response $response) : void {

        try {
            $categories = categoryModel::getCategories();
            $this->sendJson(['status' => 1,'data' => $categories]);
        } catch (\PDOException $e) {
            $response->setStatusCode(500);
            $this->sendJson(['status' => 0,'message' => 'Something went wrong']);
        }

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function addSubcategory(Request $request, Response $response) : void {

        $subcategory = new subcategoryModel();

        if(!$subcategory->validate($request->getJsonData())) {
            $response->setStatusCode(400);
            $this->sendJson(['status' => 0,'message' => $subcategory->getFirstError('categoryName')]);
            return;
        }

        $subcategory->getData($request->getJsonData());

        if(!$subcategory->save()) {
            $response->setStatusCode(500);
            $this->sendJson(['status' => 0,'message' => 'Something went wrong']);
            return;
        }

        $this->sendJson(['status' => 1,'message' => 'Subcategory added successfully']);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function viewInventoryLog(Request $request, Response $response) : void {

        $this->render('admin/inventoryLog', "Inventory Log");

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function requestPopup(Request $request, Response $response) : void {

        $requestID = $request->getJsonData()['requestID'];

        try {

            if(str_contains($requestID, 'accepted')) {
                $this->getAcceptedRequestPostedByPopup($requestID);
                return;
            }
            else {
                $this->getPendingRequestPostedByPopup($requestID);
                return;
            }

        }
        catch(\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'message' => 'Something went wrong'
            ]);
            return;
        }

    }

    /**
     * @param string $requestID
     * @return void
     */
    private function getPendingRequestPostedByPopup(string $requestID) : void {

        $sql = "SELECT *,CONCAT(r.amount,' ',s.scale) AS amount FROM request r 
                INNER JOIN users u ON r.postedBy = u.userID 
                INNER JOIN subcategory s on r.item = s.subcategoryID";

        $this->sendJson([
            'status' => 1,
            'request' => requestModel::runCustomQuery($sql, ['r.requestID' => $requestID]) [0]
        ]);

    }

    private function getAcceptedRequestPostedByPopup(string $requestID) : void {

        $sql = "SELECT *,CONCAT(r.amount,' ',s.scale) AS amount FROM acceptedrequest r 
                INNER JOIN users u ON r.postedBy = u.userID 
                INNER JOIN subcategory s on r.item = s.subcategoryID";

        $sqlUsers = "SELECT userID,username as acceptedBy FROM users WHERE userType = 'donor' 
                            UNION ALL SELECT ccID,CONCAT(city,' (CC)') as acceptedBY FROM communitycenter";

        $this->sendJson([
            'status' => 1,
            'request' => requestModel::runCustomQuery($sql, ['r.acceptedID' => $requestID]) [0],
            'users' => requestModel::runCustomQuery($sqlUsers, [],[],[],'',[],\PDO::FETCH_KEY_PAIR),
        ]);

    }

    protected function getEventPopup(Request $request,Response $response) : void {

            $eventID = $request->getJsonData()['eventID'];

            try {

                $sql = "Select *,CONCAT(c.city,' (CC)') AS cc FROM event e 
                            INNER JOIN eventcategory e2 on e.eventCategoryID = e2.eventCategoryID
                            INNER JOIN communitycenter c on e.ccID = c.ccID";

                $this->sendJson([
                    'status' => 1,
                    'event' => requestModel::runCustomQuery($sql, ['e.eventID' => $eventID]) [0]
                ]);

            }
            catch(\Exception $e) {
                $this->sendJson([
                    'status' => 0,
                    'message' => 'Something went wrong'
                ]);
                return;
            }

    }

}