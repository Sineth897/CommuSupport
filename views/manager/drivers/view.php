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

<div class="profile">
    <div class="notif-box">
        <i class="material-icons">notifications</i>
    </div>
    <div class="profile-box">
        <div class="name-box">
            <h4>Username</h4>
            <p>Position</p>
        </div>
        <div class="profile-img">
            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile">
        </div>
    </div>
</div>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Drivers"); ?>

<?php $creatEvent = \app\core\components\form\form::begin('./drivers/register', 'get'); ?>

<button class="btn-cta-primary"> Register a driver </button>

<?php $creatEvent->end(); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv(); ?>

<?php $searchDiv->filters(); ?>

<?php $searchDiv->search(); ?>

<?php $searchDiv->end(); ?>

<div id="driverDisplay"  class="main">

    <?php $driversTable = new table($headers,$arraykeys); ?>

    <?php $driversTable->displayTable($drivers); ?>

</div>


