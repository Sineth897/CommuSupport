<?php

/** @var $model \app\model\logisticModel */
/** @var $user \app\model\choModel */

$userID = \app\core\Application::session()->get('user');
$logistic = $model->retrieve();


