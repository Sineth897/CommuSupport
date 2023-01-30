<?php
use app\core\components\tables\table;
/** @var $model \app\models\choModel */

$communitycheadofice = $model->retrieve();

$header = ["District", "Email", "Address", "ContactNumber"];

$arraykey = ["district", "email", "address", "contactNumber"];

$communitycheadoficeTable=new table($header,$arraykey);

$communitycheadoficeTable->displayTable($communitycheadofice)

//echo "<pre>";
//print_r($chos);
//echo "</pre>";

?>


<?php $regForm = \app\core\components\form\form::begin('logout', 'post'); ?>

<button> logout </button>

<?php $regForm->end(); ?>

<?php $registerCho = \app\core\components\form\form::begin('./communityheadoffices/register','post') ?>

<button> Register a Cho</button>

<?php $registerCho->end(); ?>
