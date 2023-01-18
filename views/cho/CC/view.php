<?php

/** @var $model \app\models\ccModel */
/** @var $user \app\models\choModel */

$userID = \app\core\Application::session()->get('user');
$user = $user->findOne(['choId' => $userID]);
$CC = $model->retrieve(['cho' => $userID]);


echo "<pre>";
print_r($CC);
echo "</pre>";