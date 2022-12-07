<?php

/** @var $model \app\models\driverModel */

echo empty($model);

$drivers = $model->retrieve();

if( empty($drivers) ) {
    echo "No events";
} else {
    echo "<pre>";
    foreach ($drivers as $driver) {
        print_r($driver);
    }
    echo "</pre>";
}



?>

<?php $creatEvent = \app\core\components\form\form::begin('./drivers/register', 'get'); ?>

<button> Register a driver </button>

<?php $creatEvent->end(); ?>

<?php $logout = \app\core\components\form\form::begin('logout', 'post'); ?>

<button> logout </button>

<?php $logout->end(); ?>
