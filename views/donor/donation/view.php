<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<?php


?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Your Donations");

$headerDiv->pages(["active", "completed"]);

$headerDiv->end();
?>

<?php
$checkVerification = new \app\core\components\layout\verificationDiv();

if($checkVerification->notVerified()) {
    return;
}
?>

<!--        Search and filter boxes -->
<?php
$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$creatEvent = \app\core\components\form\form::begin('./donations/create', 'get');

echo "<button class='btn-cta-primary'> Donate </button>";

$creatEvent->end();

$searchDiv->end();
?>


<div class="content" id="activeDonations">
    <h3>Ongoing Donations</h3>
</div>

<div class="content" id="completedDonations">
    <h3>Completed Donations</h3>
</div>

<script type="module" src="../public/JS/donor/donation/view.js"></script>