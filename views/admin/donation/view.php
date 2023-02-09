<?php

/** @var $model \app\models\donationModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$donation = $model->retrieve();


echo "<pre>";
print_r($donation);
echo "</pre>";