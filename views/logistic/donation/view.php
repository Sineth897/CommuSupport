<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<?php

/**
 * @var $model \app\models\ccdonationModel
 * @var $user \app\models\logisticModel
 */

$user = $user->findOne(['employeeID' => $_SESSION['user']]);
$donations = $model->getDonations($user->ccID);

?>


<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Donations"); ?>

<?php $headerDiv->pages(["ongoing","completed"]); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$donationBtn = \app\core\components\form\form::begin('./donations/create', 'get');

$donationBtn->button("Request from another", "submit");

$donationBtn->end();

$searchDiv->end(); ?>

<div class="content" id="ongoingDonations">
    <?php
    echo "<pre>";
    print_r($donations);
    echo "</pre>";
    ?>
</div>

<div class="content" id="completedDonations">
    <h3>Completed Donations</h3>
</div>

<script type="module" src="../public/JS/logistic/donation/view.js"></script>