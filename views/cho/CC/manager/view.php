<?php
/** @var $model \app\models\managerModel */

$managers = $model->retrieve();

echo "<pre>";
print_r($managers);
echo "</pre>";

?>

<?php $regForm = \app\core\components\form\form::begin('logout','post'); ?>

<button> Log Out</button>

<?php $regForm->end(); ?>

<?php $registerManager= \app\core\components\form\form::begin('./cho/manager/register','post'); ?>

<button> Register a manager</button>

<?php $registerManager->end();?>



