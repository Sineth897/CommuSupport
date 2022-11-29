<?php

namespace app\models;

use app\core\userModel;

abstract class generalUser extends userModel
{
    public userModel $thisUser;

    public function __construct()
    {
        $this->thisUser = new userModel();
    }
}