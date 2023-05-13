<?php

namespace app\controller;

use app\core\Controller;
use app\core\middlewares\employeeMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\employeeModel;
use app\models\logisticModel;
use app\models\managerModel;

class employeeController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new employeeMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function viewEmployees(Request $request,Response $response) {

        $userType = $this->getUserType();
        $logistics = new logisticModel();
        $managers = new managerModel();
        $this->render($userType ."/employees/view","View employees",[
            'logistics' => $logistics,
            'managers' => $managers,
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function filterEmployees(Request $request, Response $response) : void {

        $data = $request->getJsonData();

        $filter = $data['filters'];
        $sort = $data['sort'];
        $search = $data['search'];

        try {
            $sqlLogistic  = "SELECT * FROM logisticofficer l INNER JOIN communitycenter c on l.ccID = c.ccID ";
            $sqlManager  = "SELECT * FROM manager m INNER JOIN communitycenter c on m.ccID = c.ccID ";

            $this->sendJson([
                'status' => 1,
                'logisticOfficers' => logisticModel::runCustomQuery(
                    $sqlLogistic,
                    $filter,
                    $sort,
                    [$search,['name','city']]
                ),
                'managers' => managerModel::runCustomQuery(
                    $sqlManager,
                    $filter,
                    $sort,
                    [$search,['name','city']]
                ),
            ]);
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function employeesPopup(Request $request, Response $response) : void {

        $data = $request->getJsonData();
        $employeeID = $data['employeeID'];

        try {
            if (str_contains($employeeID,'logistic')) {

                $this->sendJson([
                    'status' => 1,
                    'employee' => logisticModel::runCustomQuery(
                        'SELECT * FROM logisticofficer l INNER JOIN communitycenter c on l.ccID = c.ccID',
                        ['employeeID' => $employeeID],
                    ) [0]
                ]);

            }
            else {

                    $this->sendJson([
                        'status' => 1,
                        'employee' => managerModel::runCustomQuery(
                            'SELECT * FROM manager m INNER JOIN communitycenter c on m.ccID = c.ccID',
                            ['employeeID' => $employeeID],
                        ) [0]
                    ]);
            }
        }
        catch (\Exception $e) {
            $this->sendJson([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

    }

   
}