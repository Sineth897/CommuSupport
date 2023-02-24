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

$registerCho = \app\core\components\form\form::begin('./deliveries/create', 'get');

$registerCho->button("Create a deliveries", "submit");

$registerCho->end();

$searchDiv->end(); ?>

<div class="content" id="pendingDeliveries">

    <h3>Pending Deliveries</h3>

</div>

<div class="content" id="completedDeliveries">

    <h3>Completed Deliveries</h3>

</div>

<script type="module" src="../public/JS/logistic/deliveries/view.js"></script>