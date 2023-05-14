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
use app\models\doneeOrganizationModel;
use app\models\donorModel;
use app\models\donorOrganizationModel;
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
    protected function doneeProfile(Request $request, Response $response): void
    {

        $this->checkLink($request);
        $donee = new doneeModel();

        $this->render("donee/profile", "Your Profile", [
            'donee' => $donee
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function donorProfile(Request $request, Response $response): void
    {

        $this->checkLink($request);
        $donor = new donorModel();

        $this->render("donor/profile", "Your Profile", [
            'donor' => $donor
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function logisticProfile(Request $request, Response $response): void
    {

        $this->checkLink($request);
        $logistic = new logisticModel();

        $this->render("logistic/profile", "Your Profile", [
            'logistic' => $logistic
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function managerProfile(Request $request, Response $response): void
    {

        $this->checkLink($request);
        $manager = new managerModel();

        $this->render("manager/profile", "Your Profile", [
            'manager' => $manager
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function driverProfile(Request $request, Response $response): void
    {

        $this->checkLink($request);
        $driver = new driverModel();

        $this->render("driver/profile", "Your Profile", [
            'driver' => $driver
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function choProfile(Request $request, Response $response): void
    {

        $this->checkLink($request);
        $cho = new choModel();

        $this->render("cho/profile", "Your Profile", [
            'cho' => $cho
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws forbiddenException
     */
    protected function adminProfile(Request $request, Response $response): void
    {

        $this->checkLink($request);
        $admin = new adminModel();

        $this->render("admin/profile", "Your Profile", [
            'admin' => $admin
        ]);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function updateProfile(Request $request, Response $response): void
    {

        $data = $request->getJsonData()['data'];
        $userType = $request->getJsonData()['userType'];

        foreach ($data as $key => $value) {
            $data[$key] = trim($value);
        }

        try {

            $this->startTransaction();

            switch ($userType) {
                case 'donorOrganization':
                    $this->updateDonorOrganization($data);
                    break;
                case 'donorIndividual':
                    $this->updateDonorIndividual($data);
                    break;
                case 'doneeOrganization':
                    $this->updateDoneeOrganization($data);
                    break;
                case 'doneeIndividual':
                    $this->updateDoneeIndividual($data);
                    break;
                case 'logistic':
                    $this->updateLogistic($data);
                    break;
                case 'manager':
                    $this->updateManager($data);
                    break;
                case 'driver':
                    $this->updateDriver($data);
                    break;
                case 'cho':
                    $this->updateCho($data);
                    break;
                default:
                    throw new \Exception("Invalid user type");

            }

            $this->commitTransaction();
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            $this->sendJson([
                'message' => $e->getMessage(),
                'status' => 0
            ]);
        }

    }

    /**
     * @param $data
     * @return void
     */
    private function updateDonorOrganization($data): void
    {

        $donorTable = [];

        if (!empty($data['contactNumber'])) {
            $donorTable['contactNumber'] = $data['contactNumber'];
            $donorTable['mobileVerification'] = 0;
        }

        if (!empty($data['email'])) {
            $donorTable['email'] = $data['email'];
        }

        $donorOrganizationTable = [];

        if (!empty($data['representative'])) {
            $donorOrganizationTable['representative'] = $data['representative'];
        }

        if (!empty($data['representativeContact'])) {
            $donorOrganizationTable['representativeContact'] = $data['representativeContact'];
        }

        if (!empty($donorTable)) {
            $donorModel = new donorModel();
            $donorModel->update(['donorID' => $_SESSION['user']], $donorTable);
        }

        if (!empty($donorOrganizationTable)) {
            $donorOrganizationModel = new donorOrganizationModel();
            $donorOrganizationModel->update(['donorID' => $_SESSION['user']], $donorOrganizationTable);
        }

        $this->sendJson([
            'message' => 'Profile Updated Successfully',
            'status' => 1
        ]);

        if (!empty($data['contactNumber'])) {
            $this->setFlash('success', "Please login and verify your updated mobile number");
        }

    }

    /**
     * @param $data
     * @return void
     */
    private function updateDonorIndividual($data): void
    {

        $donorTable = [];

        if (!empty($data['contactNumber'])) {
            $donorTable['contactNumber'] = $data['contactNumber'];
            $donorTable['mobileVerification'] = 0;
        }

        if (!empty($data['email'])) {
            $donorTable['email'] = $data['email'];
        }

        if (!empty($donorTable)) {
            $donorModel = new donorModel();
            $donorModel->update(['donorID' => $_SESSION['user']], $donorTable);
        }

        $this->sendJson([
            'message' => 'Profile Updated Successfully',
            'status' => 1
        ]);

        if (!empty($data['contactNumber'])) {
            $this->setFlash('success', "Please login and verify your updated mobile number");
        }

    }

    /**
     * @param $data
     * @return void
     */
    private function updateDoneeIndividual($data): void
    {

        $doneeTable = [];

        if (!empty($data['contactNumber'])) {
            $doneeTable['contactNumber'] = $data['contactNumber'];
            $doneeTable['mobileVerification'] = 0;
        }

        if (!empty($data['email'])) {
            $doneeTable['email'] = $data['email'];
        }

        if (!empty($doneeTable)) {
            $doneeModel = new doneeModel();
            $doneeModel->update(['doneeID' => $_SESSION['user']], $doneeTable);
        }

        $this->sendJson([
            'message' => 'Profile Updated Successfully',
            'status' => 1
        ]);

        if (!empty($data['contactNumber'])) {
            $this->setFlash('success', "Please login and verify your updated mobile number");
        }

    }

    /**
     * @param $data
     * @return void
     */
    private function updateDoneeOrganization($data): void
    {

        $doneeTable = [];

        if (!empty($data['contactNumber'])) {
            $doneeTable['contactNumber'] = $data['contactNumber'];
            $doneeTable['mobileVerification'] = 0;
        }

        if (!empty($data['email'])) {
            $doneeTable['email'] = $data['email'];
        }

        $doneeOrganizationTable = [];

        if (!empty($data['representative'])) {
            $doneeOrganizationTable['representative'] = $data['representative'];
        }

        if (!empty($data['representativeContact'])) {
            $doneeOrganizationTable['representativeContact'] = $data['representativeContact'];
        }

        if (!empty($doneeTable)) {
            $doneeModel = new doneeModel();
            $doneeModel->update(['doneeID' => $_SESSION['user']], $doneeTable);
        }

        if (!empty($doneeOrganizationTable)) {
            $doneeOrganizationModel = new doneeOrganizationModel();
            $doneeOrganizationModel->update(['doneeID' => $_SESSION['user']], $doneeOrganizationTable);
        }

        $this->sendJson([
            'message' => 'Profile Updated Successfully',
            'status' => 1
        ]);

        if (!empty($data['contactNumber'])) {
            $this->setFlash('success', "Please login and verify your updated mobile number");
        }

    }

    /**
     * @param $data
     * @return void
     */
    private function updateLogistic($data) : void {

        $logisticTable = [];

        if (!empty($data['contactNumber'])) {
            $logisticTable['contactNumber'] = $data['contactNumber'];
        }

        if (!empty($data['address'])) {
            $logisticTable['address'] = $data['address'];
        }

        if (!empty($logisticTable)) {
            $logisticModel = new logisticModel();
            $logisticModel->update(['employeeID' => $_SESSION['user']], $logisticTable);
        }

        $this->sendJson([
            'message' => 'Profile Updated Successfully',
            'status' => 1
        ]);

    }

    /**
     * @param $data
     * @return void
     */
    private function updateManager($data) : void
    {

        $managerTable = [];

        if (!empty($data['contactNumber'])) {
            $managerTable['contactNumber'] = $data['contactNumber'];
        }

        if (!empty($data['address'])) {
            $managerTable['address'] = $data['address'];
        }

        if (!empty($managerTable)) {
            $managerModel = new managerModel();
            $managerModel->update(['employeeID' => $_SESSION['user']], $managerTable);
        }

        $this->sendJson([
            'message' => 'Profile Updated Successfully',
            'status' => 1
        ]);

    }

}