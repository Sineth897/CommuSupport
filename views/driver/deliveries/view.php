<link rel="stylesheet" href="../public/CSS/cards/driver-delivery-card.css">
<link rel="stylesheet" href="../public/CSS/popup/delivery-popup-styles.css">
<?php

/** @var $deliveries \app\models\deliveryModel */
/** @var $user \app\models\driverModel */

$AssignedDeliveries = $deliveries->getAssignedDeliveries($_SESSION['user']);

?>


<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Deliveries"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filterForm = \app\core\components\form\form::begin('', '');
$filterForm->dropDownList($deliveries, "Select a Category", '', \app\models\donationModel::getAllSubcategories(), 'filterCategory');
$filterForm::end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sortForm = \app\core\components\form\form::begin('', '');
$sortForm->checkBox($deliveries, "Created Date", "", 'sortCreatedDate');
$sortForm::end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->end(); ?>


<div class="card-container" id="assignedDeliveries">

    <?php

    $deliveryCard = new \app\core\components\cards\driverDeliveryCard();

    $deliveryCard->showAssignedDeliveries($AssignedDeliveries);

    ?>
</div>

<script type="module" src="../public/JS/driver/deliveries/view.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv492o7hlT-nKoy2WGWmnceYZLSw2UDWw&callback=initMap" async defer></script>
