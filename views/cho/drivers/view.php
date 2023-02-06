<?php

/** @var $driver \app\models\driverModel */
/** @var $user \app\models\choModel */

$userID = \app\core\Application::session()->get('user');
$user = $user->findOne(['employeeID'=>$userID]);
$drivers = $driver->retrieve()

