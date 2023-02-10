<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

/** @var $model \app\models\driverModel */
/** @var $user \app\models\logisticModel */


use app\core\Application;
$logisticID = Application::$app->session->get('user');
$user = $user->findOne(['employeeID' => $logisticID]);
$drivers = $model->retrieve(["ccID" => $user->ccID]);

$headers = ['Name','Contact Number','Address','Vehicle', 'Vehicle Number', 'Preference'];
$arraykeys= ['name','contactNumber','address','vehicleType', 'vehicleNo', 'preference'];

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

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>

<div id="driverDisplay" class="main">

    <?php $driversTable = new \app\core\components\tables\table($headers,$arraykeys); ?>

    <?php $driversTable->displayTable($drivers); ?>

</div>