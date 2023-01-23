<?php

/** @var $model \app\models\ccModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
$user = $user->findOne(['adminId' => $userID]);
$CC = $model->retrieve(['admin' => $userID]);


echo "<pre>
admin view cc
";
print_r($CC);
echo "</pre>";