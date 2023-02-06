<?php
/** @var $model \app\models\choModel */

$chos = $model->retrieve();

echo "<pre>";
print_r($chos);
echo "</pre>";

?>

<?php $regForm = \app\core\components\form\form::begin('logout', 'post'); ?>

<button> logout </button>

<?php $regForm->end(); ?>

<?php $registerCho = \app\core\components\form\form::begin('./communityheadoffices/register','post') ?>

<button> Register a Cho</button>

<?php $registerCho->end(); ?>
