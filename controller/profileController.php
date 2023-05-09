<?php

namespace app\controller;

use app\core\Controller;
use app\core\exceptions\forbiddenException;
use app\core\exceptions\methodNotFound;
use app\core\middlewares\managerMiddleware;
use app\core\middlewares\profileMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\adminModel;
use app\models\choModel;
use app\models\doneeModel;
use app\models\donorModel;
use app\models\driverModel;
use app\models\logisticModel;
use app\models\managerModel;

class profileController extends Controller
{
    /**
     * @param $func
     * @param Request $request
     * @param Response $response
     * @throws methodNotFound
     * @throws forbiddenException
     */
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new  profileMiddleware();
        parent::__construct($func, $request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function doneeProfile(Request $request, Response $response) : void {

        $this->checkLink($request);
        $donee = new doneeModel();

        $this->render("donee/profile","Your Profile", [
            'donee' => $donee
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function donorProfile(Request $request, Response $response) : void {

        $this->checkLink($request);
        $donor = new donorModel();

        $this->render("donor/profile","Your Profile", [
            'donor' => $donor
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function logisticProfile(Request $request, Response $response) : void {

        $this->checkLink($request);
        $logistic = new logisticModel();

        $this->render("logistic/profile","Your Profile", [
            'logistic' => $logistic
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function managerProfile(Request $request, Response $response) : void {

        $this->checkLink($request);
        $manager = new managerModel();

        $this->render("manager/profile","Your Profile", [
            'manager' => $manager
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function driverProfile(Request $request, Response $response) : void {

        $this->checkLink($request);
        $driver = new driverModel();

        $this->render("driver/profile","Your Profile", [
            'driver' => $driver
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function choProfile(Request $request, Response $response) : void {

        $this->checkLink($request);
        $cho = new choModel();

        $this->render("cho/profile","Your Profile", [
            'cho' => $cho
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function adminProfile(Request $request, Response $response) : void {

        $this->checkLink($request);
        $admin = new adminModel();

        $this->render("admin/profile","Your Profile", [
            'admin' => $admin
        ]);

    }
}