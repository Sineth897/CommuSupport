<?php

/** @var $model app\models\eventModel */

$events = $model->retrieve();

echo "<pre>";
print_r($events);
echo "</pre>";