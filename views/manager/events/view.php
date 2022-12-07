<?php

/** @var $model \app\models\eventModel */

echo empty($model);

$events = $model->retrieve();

if( empty($events) ) {
    echo "No events";
} else {
    echo "<pre>";
    foreach ($events as $event) {
        print_r($event);
    }
    echo "</pre>";
}



?>

<?php $creatEvent = \app\core\components\form\form::begin('./events/create', 'get'); ?>

<button> Create event </button>

<?php $creatEvent->end(); ?>

<?php $logout = \app\core\components\form\form::begin('logout', 'post'); ?>

<button> logout </button>

<?php $logout->end(); ?>
