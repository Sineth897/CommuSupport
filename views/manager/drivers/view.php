<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<?php

/** @var $model \app\models\driverModel */

use app\core\components\tables\table;

echo empty($model);

$drivers = $model->retrieve();

if( empty($drivers) ) {
    echo "No drivers currently registered";
}

$headers = ['Name','Contact Number','Address','Vehicle', 'Vehicle Number', 'Preference'];
$arraykeys= ['name','contactNumber','address','vehicleType', 'vehicleNo', 'preference'];

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Drivers"); ?>

<?php $creatEvent = \app\core\components\form\form::begin('./drivers/register', 'get'); ?>

<button class="btn-cta-primary"> Register a driver </button>

<?php $creatEvent->end(); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv(); ?>

<?php $searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();?>

<?php $searchDiv->search(); ?>

<?php $searchDiv->end(); ?>

<div id="driverDisplay"  class="main">

    <?php $driversTable = new table($headers,$arraykeys); ?>

    <?php $driversTable->displayTable($drivers); ?>

</div>


