<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<?php


?>


<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

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
    <h3>Ongoing Donations</h3>
</div>

<div class="content" id="completedDonations">
    <h3>Completed Donations</h3>
</div>

<script type="module" src="../public/JS/logistic/donation/view.js"></script>