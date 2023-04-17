<link rel="stylesheet" href="../public/CSS/cards/delivery-card.css">
<link rel="stylesheet" href="../public/CSS/popup/delivery-popup-styles.css">
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

$searchDiv->end(); ?>

<div class="content">

        <div class="card-container">
            <?php
            $deliveryCard = new \app\core\components\cards\deliveryCard();
            $deliveryCard->showDeliveryCard($deliveries['directDonations'],"directDonations");
            $deliveryCard->showDeliveryCard($deliveries['acceptedRequests'],"acceptedRequests");
            $deliveryCard->showDeliveryCard($deliveries['ccDonations'],"ccDonations");
            ?>


        </div>
</div>


<script type="module" src="../public/JS/logistic/deliveries/view.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv492o7hlT-nKoy2WGWmnceYZLSw2UDWw&callback=initMap" async defer></script>