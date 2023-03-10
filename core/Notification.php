<?php

namespace app\core;

use app\models\notificationModel;

class Notification
{
    private notificationModel $notification;

    public function __construct()
    {
        $this->notification = new notificationModel();
    }


}