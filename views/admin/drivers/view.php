<?php

/** @var $model \app\models\driverModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$driver = $model->retrieve();


echo "<pre>";
print_r($driver);
echo "</pre>";