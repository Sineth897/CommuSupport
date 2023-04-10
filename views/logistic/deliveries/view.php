<link rel="stylesheet" href="../public/CSS/cards/delivery-card.css">
<?php

/** @var $deliveries \app\models\deliveryModel */
/** @var $user \app\models\logisticModel */

//should show direct donations
//should show accepted requests
//should show ccdonations

$deliveries = $user->getPendingDeliveries();


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

$deliveryBtn = \app\core\components\form\form::begin('./deliveries/create', 'get');

$deliveryBtn->button("Create a deliveries", "submit");

$deliveryBtn->end();

$searchDiv->end(); ?>

<!--<div class="content" id="pendingDeliveries">-->

        <div class="card-container">
            <?php
            $deliveryCard = new \app\core\components\cards\deliveryCard();
            $deliveryCard->showDeliveryCard($deliveries['directDonations'],"directDonations");
            $deliveryCard->showDeliveryCard($deliveries['acceptedRequests'],"acceptedRequests");
            $deliveryCard->showDeliveryCard($deliveries['ccDonations'],"ccDonations");
            ?>
        </div>


<!--</div>-->

<!--<div class="content" id="completedDeliveries">-->
<!---->
<!--    <h3>Completed Deliveries</h3>-->
<!---->
<!--</div>-->

<script type="module" src="../public/JS/logistic/deliveries/view.js"></script>