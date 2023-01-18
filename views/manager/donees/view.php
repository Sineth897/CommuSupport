<?php

/**
 * @var $model \app\models\doneeModel
 * @var $user \app\models\managerModel
 */

use app\core\Application;
use app\core\components\tables\table;
use app\models\managerModel;

$manager = managerModel::getUser(['employeeID' => Application::session()->get('user')]);
$donees = $model->getAllDonees($manager->ccID);

$individualDoneeHeaders = ["First Name","Last Name","Age","Contact Number","Email","Address"];
$individualDoneeArrayKeys = ["firstName","lastName","age","contactNumber","email","address"];

$organizationDoneeHeaders = ["Organization Name","Representative Name","Contact Number","Email","Address"];
$organizationDoneeArrayKeys = ["organizationName","representative","contactNumber","email","address"];


?>

<div id="individualDoneeDisplay">

    <?php $individualTable = new table($individualDoneeHeaders,$individualDoneeArrayKeys); ?>

    <?php

    if($donees['individuals']) {
        $individualTable->displayTable($donees['indiviuals']);
    } else {
        echo "No Individual Donees";
    } ?>

</div>

<div id="organizationDoneeDisplay">

    <?php $organizationTable = new table($organizationDoneeHeaders,$organizationDoneeArrayKeys); ?>

    <?php

    if($donees['organizations']) {
        $organizationTable->displayTable($donees['organizations']);
    } else {
        echo "No Organizations";
    } ?>

</div>
