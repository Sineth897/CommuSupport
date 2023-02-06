<?php

/** @var $model \app\models\requestModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$request = $model->retrieve();


echo "<pre>";
print_r($request);
echo "</pre>";