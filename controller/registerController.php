<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\registerMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\complaintModel;
use app\models\doneeIndividualModel;
use app\models\doneeModel;
use app\models\doneeOrganizationModel;
use app\models\donorIndividualModel;
use app\models\donorModel;
use app\models\donorOrganizationModel;
use app\models\driverModel;
use app\models\managerModel;
use app\models\userModel;

class registerController extends Controller
{
    public function __construct($func, Request $request, Response $response)
    {
        $this->middleware = new registerMiddleware();
        parent::__construct($func, $request, $response);
    }

    protected function registerDriver(Request $request, Response $response)
    {
        $this->checkLink($request);

        $driver = new driverModel();
        $user = new userModel();

        if ($request->isPost()) {
            $driver->getData($request->getBody());
            $user->getData($request->getBody());
            if ($driver->validate($request->getBody()) && $user->validate($request->getBody())) {
                $driver->setUser($user);
                try {
                    $this->startTransaction();
                    if ($driver->save()) {
                        $this->setFlash('success', 'Driver registered successfully');
                        $driver->reset();
                        $user->reset();
                    }
                    $this->commitTransaction();
                } catch (\Exception $e) {
                    $this->rollbackTransaction();
                    $this->setFlash('error', 'Unable to save on database');
                }
            } else {
                $this->setFlash('error', 'Validation failed');
            }
        }

        $this->render("manager/drivers/register", "Register a Driver", [
            'driver' => $driver,
            'user' => $user
        ]);

    }

    protected function registerCho(Request $request, Response $response)
    {
        $cho = new \app\models\choModel();
        $user = new \app\models\userModel();

        $this->checkLink($request);

        if ($request->isPost()) {

            $cho->getData($request->getBody());
            $user->getData($request->getBody());
            if ($cho->validate($request->getBody()) && $user->validate($request->getBody())) {
                $cho->setUser($user);
                try {
                    $this->startTransaction();
                    if ($cho->save()) {
                        $this->setFlash('success', 'Community Head Office registered successfully');
                        $cho->reset();
                        $user->reset();
                    }
                    $this->commitTransaction();
                    $response->redirect('/admin/communityheadoffices');
                } catch (\Exception $e) {
                    $this->rollbackTransaction();
                    $this->setFlash('error', 'Unable to save on database');
                }
            } else {
                $this->setFlash('error', 'Validation failed');
            }
        }

        $this->render("admin/communityheadoffices/register", "Register a Community Head Office", [
            'cho' => $cho,
            'user' => $user
        ]);
    }

    protected function registerDonor(Request $request, Response $response)
    {
        $donor = new \app\models\donorModel();
        $user = new \app\models\userModel();
        $donorIndividual = new \app\models\donorIndividualModel();
        $donorOrganization = new \app\models\donorOrganizationModel();

        if ($request->isPost()) {
            $data = $request->getBody();
            $donor->getData($data);
            $user->getData($data);
            if ($this->validateDonor($data, $user, $donor, $donorIndividual, $donorOrganization)) {
                try {
                    $this->startTransaction();
                    if ($donor->saveOnALL($data)) {
                        $this->setFlash('success', 'Donor registered successfully. Please verify your mobile number to complete registration');
                        $donor->reset();
                        $user->reset();
                        $this->commitTransaction();
                        $response->redirect('/login/user');
                    }
                } catch (\Exception $e) {
                    $this->rollbackTransaction();
                    $this->setFlash('error', 'Unable to save on database');
                }
            } else {
                $this->setFlash('error', 'Validation failed');
            }

        }

        $this->render("guest/register/donor", "Register as a Donor", [
            'donor' => $donor,
            'user' => $user,
            'donorIndividual' => $donorIndividual,
            'donorOrganization' => $donorOrganization
        ]);
    }

    private function validateDonor($data, userModel $user, donorModel $donor, donorIndividualModel $donorIndividual, donorOrganizationModel $donorOrganization): bool
    {
        if ($data['type'] === "Individual") {
            $donorIndividual->getData($data);
            if ($donor->validate($data) && $user->validate($data) && $donorIndividual->validate($data)) {
                return true;
            }
            return false;
        } else {
            $donorOrganization->getData($data);
            if ($donor->validate($data) && $user->validate($data) && $donorOrganization->validate($data)) {
                return true;
            }
            return false;
        }
    }

    protected function registerDonee(Request $request, Response $response)
    {
        $donee = new doneeModel();
        $user = new userModel();
        $doneeIndividual = new doneeIndividualModel();
        $doneeOrganization = new doneeOrganizationModel();

        if ($request->isPost()) {
            $data = $request->getBody();
            $donee->getData($data);
            $user->getData($data);
            if ($this->validateDonee($data, $user, $donee, $doneeIndividual, $doneeOrganization)) {
                try {
                    $this->startTransaction();
                    if ($donee->saveOnALL($data)) {
                        $manager = managerModel::getModel(['ccID' => $donee->ccID ]);
                        $this->setNotification("Donee with the username {$user->username} registered under your community center",
                            'New registration',
                            $manager->employeeID,'',
                            'registration',$user->userID);
                        $donee->reset();
                        $user->reset();
                        $this->commitTransaction();
                        $this->setFlash('success', 'Donee registered successfully. Please verify your mobile number to complete registration');
                        $response->redirect('/login/user');
                    }
                } catch (\Exception $e) {
                    $this->rollbackTransaction();
                    $this->setFlash('error', 'Unable to save on database');
                }
            } else {
                $this->setFlash('error', 'Validation failed');
            }
        }

        $this->render('guest/register/donee', "Register as a Donee", [
            'donee' => $donee,
            'user' => $user,
            'doneeIndividual' => $doneeIndividual,
            'doneeOrganization' => $doneeOrganization,
        ]);

    }

    private function validateDonee(array $data, userModel $user, doneeModel $donee, doneeIndividualModel $doneeIndividual, doneeOrganizationModel $doneeOrganization)
    {
        if ($data['type'] === "Individual") {
            $doneeIndividual->getData($data);
            if ($donee->validate($data) && $user->validate($data) && $doneeIndividual->validate($data)) {
                return true;
            }
            return false;
        } else {
            $doneeOrganization->getData($data);
            if ($donee->validate($data) && $user->validate($data) && $doneeOrganization->validate($data)) {
                return true;
            }
            return false;
        }
    }

    protected function registerCC(Request $request, Response $response)
    {
        $cc = new \app\models\ccModel();


        if ($request->isPost()) {
            $cc->getData($request->getBody());
            if ($cc->validate($request->getBody())) {
                if ($cc->save()) {
                    $this->setFlash('success', 'Community Center Registered successfully');
                    $cc->reset();
                } else {
                    $this->setFlash('Error', 'Unable to save on database');
                }
            } else {
                $this->setFlash('Error', 'Validation failed');
            }
        }

        $this->render("cho/CC/register", "Register a Community Center", [
            'cc' => $cc,
        ]);
    }


    protected function registerManager(Request $request, Response $response)
    {
        $this->checkLink($request);
        $manager = new \app\models\managerModel();
        $user = new \app\models\userModel();

        if ($request->isPost()) {
            $manager->getData($request->getBody());
            $user->getData($request->getBody());
            $user->userType = 'manager';
            $manager->employeeID= substr(uniqid('manager',true),0,23);
            $user->userID=$manager->employeeID;
            $user->password=password_hash($user->password,PASSWORD_DEFAULT);
            if ($manager->validate($request->getBody()) && $user->validate($request->getBody())) {
                try {
                    $this->startTransaction();
                    if ($manager->save() && $user->save()) {
                        $this->setFlash('success', 'Manager registered successfully');
                        $manager->reset();
                        $user->reset();
                        $this->commitTransaction();
                        $response->redirect('/cho/communitycenters');
                    }


                } catch (\Exception $e) {
                    $this->rollbackTransaction();
                    $this->setFlash('Error', 'Unable to save on the database');
                    echo $e->getMessage();
                }
            } else {
                $this->setFlash('Error', 'Validation Failed');
            }
        }
        if($request->isGet()){

            $manager->ccID= $_GET['ccID'];

        }
        $this->render("cho/manager/register", "Register a Manager", [
            'manager' => $manager,
            'user' => $user,
        ]);

    }


    protected function registerLogistic(Request $request, Response $response)
    {
        $this->checkLink($request);
        $logistic = new \app\models\logisticModel();
        $user = new \app\models\userModel();

        if ($request->isPost()) {
            $logistic->getData($request->getBody());
            $user->getData($request->getBody());
            $user->userType = 'logistic';
            $logistic->employeeID = substr(uniqid('manager',true),0,23);
            $user->userID=$logistic->employeeID;
            $user->password=password_hash($user->password,PASSWORD_DEFAULT);

            if ($logistic->validate($request->getBody()) && $user->validate($request->getBody())) {
                try {
                    $this->startTransaction();
                    if ($logistic->save() && $user->save()) {
                        $this->setFlash('success', 'Logistic Manager registered successfully');
                        $logistic->reset();
                        $user->reset();
                        $this > $this->commitTransaction();
                        $response->redirect('/cho/communitycenters');
                    }


                } catch (\Exception $e) {
                    $this->rollbackTransaction();
                    $this->setFlash('Error', 'Unable to save on the database');
                    echo $e->getMessage();
                }
            } else {
                $this->setFlash('Error', 'Validation failed');
            }
        }
        if($request->isGet()){

            $logistic->ccID = $_GET['ccID'];
        }
        $this->render("cho/logistic/register", "Register a Logistic Manager", [
            'logistic' => $logistic,
            'user' => $user,
        ]);
    }

}