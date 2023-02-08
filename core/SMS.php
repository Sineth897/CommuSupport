<?php

namespace app\core;

use app\models\choModel;
use app\models\doneeModel;
use app\models\donorModel;
use app\models\driverModel;
use app\models\logisticModel;
use app\models\managerModel;
use app\models\userModel;

class SMS
{
    private string $id = '';
    private string $pw = '';
    private string $baseURL = 'http://www.textit.biz/sendmsg';

    public function __construct(array $config)
    {
        $this->id = $config['id'];
        $this->pw = $config['pw'];
    }

    private function sendSMS(string $to, string $msg): bool
    {
        $msg = urlencode($msg);
        $url = $this->baseURL . "?id=" . $this->id . "&pw=" . $this->pw . "&to=$to&text=$msg";
        $returnMsg = file($url);
        if (trim($returnMsg[0]) == "OK") {
            return true;
        } else {
            return false;
        }
    }

    private function getUserModel(userModel $user)
    {
        $userClass = null;
        switch ($user->userType) {
            case 'donee':
                $userClass = doneeModel::getModel(['doneeID' => $user->userID]);
                break;
            case 'donor':
                $userClass = donorModel::getModel(['donorID' => $user->userID]);
                break;
            case 'driver':
                $userClass = driverModel::getModel(['driverID' => $user->userID]);
                break;
            case 'logistic':
                $userClass = logisticModel::getModel(['logisticID' => $user->userID]);
                break;
            case 'manager':
                $userClass = managerModel::getModel(['managerID' => $user->userID]);
                break;
            case 'cho':
                $userClass = choModel::getModel(['choID' => $user->userID]);
                break;
        }
        return $userClass;
    }

    public function send(string $msg, userModel $user): bool
    {
        $user = $this->getUserModel($user);
        return $this->sendSMS($user->contactNumber, $msg);
    }


}