<?php

/**
 * @var $model \app\models\doneeModel
 * @var $user \app\models\managerModel
 */

use app\core\Application;

$managerID = Application::$app->session->get('user');
$manager = $user->findOne(['employeeID' => $managerID]);
$donees = $model->getAllDonees($manager->ccID);

echo "<pre>";
var_dump($donees);
echo "</pre>";


?>