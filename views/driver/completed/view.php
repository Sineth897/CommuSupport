<link rel="stylesheet" href="../../public/CSS/cards/driver-delivery-card.css">

<?php

/**
 * @var $deliveryModel \app\models\deliveryModel
 * @var $user \app\models\driverModel
 */

$CompletedDeliveries = $deliveryModel->getCompletedDeliveriesByDriverID($_SESSION['user']);

?>


<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Completed Deliveries"); ?>

<?php //$headerDiv->pages(["assigned", "completed"]); ?>

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

         \app\core\components\cards\driverDeliveryCard::showCompletedDeliveryCards($CompletedDeliveries);

         ?>

    </div>


</div>

<script type="module" src="../../public/JS/driver/completed/view.js"></script>
