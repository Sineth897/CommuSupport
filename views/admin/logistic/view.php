<?php

/** @var $model \app\models\logsticModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$logistic = $model->retrieve();


echo "<pre>";
print_r($logistic);
echo "</pre>";