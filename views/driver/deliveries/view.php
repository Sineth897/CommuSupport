
<?php

/** @var $deliveries \app\models\deliveryModel */
/** @var $user \app\models\userModel */

echo "Hello, " . $_SESSION['username'];

?>


<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Inventory"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd(); ?>

<button id="addBtn" class="btn-cta-primary">Add Item</button>

<?php $searchDiv->end(); ?>