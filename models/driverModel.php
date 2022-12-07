<?php

namespace app\models;

use app\core\Application;
use app\core\DbModel;

class driverModel extends DbModel
{
    protected userModel $user;
    public string $employeeID = '';
    public string $name = '';
    public int $age = 0;
    public string $NIC = '';
    public string $gender = '';
    public string $address = '';
    public string $contactNumber = '';
    public string $ccID = '';
    public string $licenseNo = '';
    public string $vehicleNo = '';
    public string $vehicleType = '';
    public string $preference = '';


    public function table(): string
    {
        return 'driver';
    }

    public function attributes(): array
    {
        return ['employeeID', 'name', 'age', 'NIC','gender', 'address', 'contactNumber', 'ccID', 'licenseNo', 'vehicleNo', 'vehicleType', 'preference'];
    }

    public function primaryKey(): string
    {
        return 'employeeID';
    }

    public function rules(): array
    {
        return [
            'name'  => [self::$REQUIRED],
            'age' => [self::$REQUIRED],
            'NIC' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'gender' => [self::$REQUIRED],
            'address' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'contactNumber' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'licenseNo' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'vehicleNo' => [self::$REQUIRED, [self::$UNIQUE, 'class' => self::class]],
            'vehicleType' => [self::$REQUIRED],
            'preference' => [self::$REQUIRED],
        ];
    }

    public function save(): bool
    {
        $this->employeeID = uniqid('driver',true);
        $manager = managerModel::getUser(['employeeID' => Application::session()->get('user')]);
        $this->ccID = $manager->ccID;

        if(parent::save()) {
            if($this->user->save()) {
                return true;
            }
            else {
                $this->deleteOne(['employeeID' => $this->employeeID]);
            }
        }
        return false;
    }

    public function getVehicleTypes(): array
    {
        return [
            'Bike' => 'Bike',
            'Three-wheeler' => 'Three-wheeler',
            'Car'=> 'Car',
            'Van' => 'Van',
        ];
    }

    public function getPreferences(): array {
        return [
            'Short' => 'Short distances',
            'Long' => 'Long distances',
            'Both' => 'Both',
        ];
    }

    public function setUser(userModel $user) {
        $this->user = $user;
        $this->user->userType = "driver";
        $this->user->userID = $this->employeeID;
    }
}