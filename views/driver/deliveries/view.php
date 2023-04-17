<link rel="stylesheet" href="../public/CSS/cards/driver-delivery-card.css">
<?php

/** @var $deliveries \app\models\deliveryModel */
/** @var $user \app\models\driverModel */

$AssignedDeliveries = $deliveries->getAssignedDeliveries($_SESSION['user']);

?>


<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Deliveries"); ?>

<?php $headerDiv->pages(["assigned", "completed"]); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->end(); ?>


<div class="card-container">

    <?php

//    echo "<pre>";
//    print_r($AssignedDeliveries);
//    echo "</pre>";

    $deliveryCard = new \app\core\components\cards\driverDeliveryCard();

    $deliveryCard->showAssignedDeliveries($AssignedDeliveries);



    ?>

<!--    <div class="driver-del-card">-->
<!--        <div class="card-column subcategory"><strong>Sub Category</strong><p>Gas Cylinder</p></div>-->
<!--        <div class="card-column pickupaddress"><strong>Pick up</strong><p>123/5, Colombo, Colombo</p></div>-->
<!--        <div class="card-column deliveryaddress"><strong>Drop Off</strong><p>123/5, Colombo, Colombo</p></div>-->
<!--        <div class="card-column assigneddate"><strong>Created Date</strong><p>2023-01-45</p></div>-->
<!--        <div class="card-column route-complete-btns">-->
<!--            <a class="del-route" href="#">Route</a>-->
<!--            <a class="del-accept" href="#">Accept</a>-->
<!--        </div>-->
<!--        <div class="card-column delivery-btns">-->
<!--            <a class="del-decline" href="#">Request to Re-Assign</a>-->
<!--        </div>-->
<!--    </div>-->
</div>
