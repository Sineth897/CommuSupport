<?php

/**
 * @var $model \app\models\doneeModel
 * @var $user \app\models\managerModel
 */

use app\core\Application;

$manager = \app\models\managerModel::getUser(['employeeID' => Application::$app->session->get('user')]);
$donors = $model->getAllDonees($manager->ccID);

echo "<pre>";
var_dump($donors);
echo "</pre>";


?>