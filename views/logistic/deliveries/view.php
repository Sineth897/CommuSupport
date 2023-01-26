<?php

/** @var $deliveries \app\models\deliveryModel */
/** @var $user \app\models\logisticModel */

echo "Hello, " . $_SESSION['username'];

echo "<pre>";
var_dump($deliveries->retrieve());
echo "</pre>";


?>

<?php $regForm = \app\core\components\form\form::begin('logout', 'post'); ?>

    <button> logout </button>

<?php $regForm->end(); ?>