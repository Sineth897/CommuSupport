<?php

/** @var $model \app\models\managerModel */
/** @var $user \app\models\choModel */

$userID = \app\core\Application::session()->get('user');

$manager = $model->retrieve(['employee' => $userID]);


echo "<pre>";
print_r($manager);
echo "</pre>";