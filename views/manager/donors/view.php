<?php

/**
 * @var $model \app\models\donorModel
 * @var $user \app\models\managerModel
 */

use app\core\Application;

$managerID = Application::$app->session->get('user');
$manager = $user->findOne(['employeeID' => $managerID]);
$donors = $model->getAllDonors($manager->ccID);

echo "<pre>";
var_dump($donors);
echo "</pre>";

?>