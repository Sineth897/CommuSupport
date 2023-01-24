<?php

/** @var $model \app\models\driverModel */

use app\core\components\tables\table;

echo empty($model);

$drivers = $model->retrieve();

if( empty($drivers) ) {
    echo "No drivers currently registered";
}

$headers = ['Name','Age','Contact Number','Address','Vehicle','Preference'];
$arraykeys= ['name','age','contactNumber','address','vehicleType','preference'];

?>

<?php $creatEvent = \app\core\components\form\form::begin('./drivers/register', 'get'); ?>

<button> Register a driver </button>

<?php $creatEvent->end(); ?>

<button type="button"> Filter </button>

<div id="driverDisplay">

    <?php $driversTable = new table($headers,$arraykeys); ?>

    <?php $driversTable->displayTable($drivers); ?>

</div>


