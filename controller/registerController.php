<?php

namespace app\controller;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\registerMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\doneeIndividualModel;
use app\models\doneeModel;
use app\models\doneeOrganizationModel;
use app\models\donorIndividualModel;
use app\models\donorModel;
use app\models\donorOrganizationModel;
use app\models\driverModel;
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
        $driver = new driverModel();
        $user = new userModel();

        if($request->isPost()) {
            $driver->getData($request->getBody());
            $user->getData($request->getBody());
            if($driver->validate($request->getBody()) && $user->validate($request->getBody())) {
                $driver->setUser($user);
                if($driver->save()) {
                        $this->setFlash('success', 'Driver registered successfully');
                        $driver->reset();
                        $user->reset();
                }
                $this->setFlash('Error', 'Unable to save on database');
            }
            else {
                $this->setFlash('Error', 'Validation failed');
            }
        }

        $this->render("manager/drivers/register", "Register a Driver", [
            'driver' => $driver,
            'user' => $user
        ]);

    }

    protected function registerCho(Request $request,Response $response) {
        $cho = new \app\models\choModel();
        $user = new \app\models\userModel();

        if($request->isPost()) {
            $cho->getData($request->getBody());
            $user->getData($request->getBody());
            if($cho->validate($request->getBody()) && $user->validate($request->getBody())) {
                $cho->setUser($user);
                if($cho->save()) {
                        $this->setFlash('success', 'Community Head Office registered successfully');
                        $cho->reset();
                        $user->reset();
                }
                $this->setFlash('Error', 'Unable to save on database');
            }
            else {
                $this->setFlash('Error', 'Validation failed');
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

        if($request->isPost()) {
            $data = $request->getBody();
            $donor->getData($data);
            $user->getData($data);
            if($this->validateDonor($data,$user,$donor,$donorIndividual,$donorOrganization)) {
                if($donor->saveOnALL($data)) {
                    $this->setFlash('success', 'Donor registered successfully. Please verify your mobile number to complete registration');
                    $donor->reset();
                    $user->reset();
                }
                $this->setFlash('Error', 'Unable to save on database');
            }
            else {
                $this->setFlash('Error', 'Validation failed');
            }

        }

        $this->render("guest/register/donor", "Register as a Donor", [
            'donor' => $donor,
            'user' => $user,
            'donorIndividual' => $donorIndividual,
            'donorOrganization' => $donorOrganization
        ]);
    }

    private function validateDonor($data,userModel $user,donorModel $donor,donorIndividualModel $donorIndividual,donorOrganizationModel $donorOrganization):bool {
        if($data['type'] === "Individual") {
            $donorIndividual->getData($data);
            if($donor->validate($data) && $user->validate($data) && $donorIndividual->validate($data)) {
                return true;
            }
            return false;
        }
        else {
            $donorOrganization->getData($data);
            if($donor->validate($data) && $user->validate($data) && $donorOrganization->validate($data)) {
                return true;
            }
            return false;
        }
    }

    protected function registerDonee(Request $request,Response $response) {
        $donee = new doneeModel();
        $user = new userModel();
        $doneeIndividual = new doneeIndividualModel();
        $doneeOrganization = new doneeOrganizationModel();

        if($request->isPost()) {

        }

        $this->render('guest/register/donee',"Register as a Donee", [
            'donee' => $donee,
            'user' => $user,
            'doneeIndividual' => $doneeIndividual,
            'doneeOrganization' => $doneeOrganization,
        ]);

    }

    protected function verifyMobile(Request $request, Response $response)
    {
        //
    }

    protected function registerManager(Request $request, Response $response)
    {
        // TODO: Implement registerManager() method.

    }

    protected function registerLogistic(Request $request, Response $response)
    {
        // TODO: Implement registerLogistic() method.

    }



}