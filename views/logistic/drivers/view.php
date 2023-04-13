<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

/** @var $model \app\models\driverModel */
/** @var $user \app\models\logisticModel */


use app\core\Application;
$logisticID = Application::$app->session->get('user');
$user = $user->findOne(['employeeID' => $logisticID]);
$drivers = $model->retrieve(["ccID" => $user->ccID]);

$headers = ['Name','Contact Number','Vehicle', 'Vehicle Number', 'Preference'];
$arraykeys= ['name','contactNumber','vehicleType', 'vehicleNo', 'preference',['','View','#',[],'driverID']];

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Drivers"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model,"Vehicle Type","vehicleType",$model->getVehicleTypes(),"vehicleTypeFilter");
$filter->dropDownList($model,"Preference","preference",$model->getPreferences(),"preferenceFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model,"Age","age","ageSort");
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>

<div id="driverDisplay" class="content">

    <?php $driversTable = new \app\core\components\tables\table($headers,$arraykeys); ?>

    <?php $driversTable->displayTable($drivers); ?>


</div>

<script type="module" src="../public/JS/logistic/drivers/view.js"></script>