<?php

/** @var $model \app\models\employeeModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$employee = $model->retrieve();


echo "<pre>";
print_r($employee);
echo "</pre>";