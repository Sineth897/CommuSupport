<?php
/** @var $inventory \app\models\inventoryModel */
/** @var $user \app\models\logisticModel */

use app\core\Application;

$user = $user->findOne(['employeeID' => Application::session()->get('user')]);
$Items = $inventory->retrieve(['ccID' => $user->ccID]);

var_dump($Items);

?>

<?php 
