<?php

namespace app\models;

use app\core\UserModel;

abstract class generalUser extends UserModel
{
    public UserModel $thisUser;

    public function __construct()
    {
        $this->thisUser = new UserModel();
    }
}