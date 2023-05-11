<link rel="stylesheet" href="../public/CSS/cards/delivery-card.css">
<link rel="stylesheet" href="../public/CSS/popup/delivery-popup-styles.css">
<?php

/** @var $deliveries \app\models\deliveryModel */
/** @var $user \app\models\logisticModel */

//should show direct donations
//should show accepted requests
//should show ccdonations
// contains all deliveries related to logistic officer's community center
$delivery = $user->getPendingDeliveries();

// filter out pending deliveries
$pendingDeliveries['directDonations'] = array_filter($delivery['directDonations'], function($delivery) {
    return $delivery['status'] !== "Completed";
});

$pendingDeliveries['acceptedRequests'] = array_filter($delivery['acceptedRequests'], function($delivery) {
    return $delivery['status'] !== "Completed";
});

$pendingDeliveries['ccDonations'] = array_filter($delivery['ccDonations'], function($delivery) {
    return $delivery['status'] !== "Completed";
});

// filter out completed deliveries
$completedDeliveries['directDonations'] = array_filter($delivery['directDonations'], function($delivery) {
    return $delivery['status'] === "Completed";
});

$completedDeliveries['acceptedRequests'] = array_filter($delivery['acceptedRequests'], function($delivery) {
    return $delivery['status'] === "Completed";
});

$completedDeliveries['ccDonations'] = array_filter($delivery['ccDonations'], function($delivery) {
    return $delivery['status'] === "Completed";
});

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Pending Deliveries"); ?>

<?php $headerDiv->pages(["pending", "completed"]); ?>

<?php $headerDiv->end(); ?>

<?php
$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filterForm = \app\core\components\form\form::begin('', '');
$filterForm->dropDownList($deliveries, "Select a Category", '', \app\models\donationModel::getAllSubcategories(), 'filterCategory');
$filterForm->dropDownList($deliveries, "Select a Process", '', ['acceptedRequest' => 'Requests','donation' => 'Donations','ccDonation' => 'CC Donations' ], 'filterProcess');
$filterForm::end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sortForm = \app\core\components\form\form::begin('', '');
$sortForm->checkBox($deliveries,"Date","",'sortCreatedDate');
$sortForm::end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->end(); ?>

<div class="content">

        <div class="card-container" id="pendingDeliveryDiv" >
            <?php
            $deliveryCard = new \app\core\components\cards\deliveryCard();
            $deliveryCard->showDeliveryCard($pendingDeliveries['directDonations'],"directDonations");
            $deliveryCard->showDeliveryCard($pendingDeliveries['acceptedRequests'],"acceptedRequests");
            $deliveryCard->showDeliveryCard($pendingDeliveries['ccDonations'],"ccDonations");
            ?>
        </div>

        <div class="card-container" id="completedDeliveryDiv" style="display: none;">
            <?php
            $deliveryCard = new \app\core\components\cards\deliveryCard();
            $deliveryCard->showDeliveryCard($completedDeliveries['directDonations'],"directDonations");
            $deliveryCard->showDeliveryCard($completedDeliveries['acceptedRequests'],"acceptedRequests");
            $deliveryCard->showDeliveryCard($completedDeliveries['ccDonations'],"ccDonations");
            ?>
        </div>
</div>


<script type="module" src="../public/JS/logistic/deliveries/view.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv492o7hlT-nKoy2WGWmnceYZLSw2UDWw&callback=initMap" async defer></script>