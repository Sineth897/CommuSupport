<?php

/** @var $model \app\models\ccModel */
/** @var $user \app\models\donorModel */



$CC = $model->retrieve();


echo "<pre>";
print_r($CC);
echo "</pre>";