<?php

/** @var $model \app\models\managerModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$manager = $model->retrieve();


echo "<pre>";
print_r($manager);
echo "</pre>";