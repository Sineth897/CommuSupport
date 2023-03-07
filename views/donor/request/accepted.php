<?php

/** @var $model \app\models\acceptedModel */
/** @var $user \app\models\donorModel */



$acceptedrequest = $model->retrieve();


echo "<pre>";
print_r($acceptedrequest);
echo "</pre>";