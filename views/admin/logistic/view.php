<?php
use app\core\components\tables\table;

/** @var $model \app\models\logisticModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$logistic = $model->retrieve();


echo "<pre>";
print_r($logistic);
echo "</pre>";