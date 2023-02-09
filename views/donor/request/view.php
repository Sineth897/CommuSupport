<?php

/** @var $model \app\models\requestModel */
/** @var $user \app\models\donorModel */

$userID = \app\core\Application::session()->get('user');
$user = $user->findOne(['donorId' => $userID]);
$request = $model->retrieve(['donor' => $userID]);


echo "<pre>";
print_r($request);
echo "</pre>";