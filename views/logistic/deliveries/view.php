<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<?php

/** @var $deliveries \app\models\deliveryModel */
/** @var $user \app\models\logisticModel */

//echo "<pre>";
//var_dump($deliveries->retrieve());
//echo "</pre>";


?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Deliveries"); ?>

<?php $headerDiv->pages(["pending", "completed"]); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$registerCho = \app\core\components\form\form::begin('./delivery/create', 'get');

$registerCho->button("Create a delivery", "submit");

$registerCho->end();

?>

<?php $searchDiv->end(); ?>
