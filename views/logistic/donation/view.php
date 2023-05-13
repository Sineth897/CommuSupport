<link rel="stylesheet" href="/CommuSupport/public/CSS/cards/donor-donation-card.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">

<?php
    /** @var $model \app\models\donationModel */
    /** @var $user \app\models\logisticModel */

$user = $user->findOne(['employeeID' => $_SESSION['user']]);

$donations = $model->getDonationsFromDonorsToViewByEmployees($user->ccID);

$ongoingDonations = array_filter($donations, function($donation) {
    return $donation['deliveryStatus'] === "Not Assigned" || $donation['deliveryStatus'] === "Ongoing";
});

$completedDonations = array_filter($donations, function($donation) {
    return $donation['deliveryStatus'] === 'Completed';
});

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Ongoing Donations"); ?>

<?php $headerDiv->pages(["ongoing","completed"]); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filterForm = \app\core\components\form\form::begin('', '');
$filterForm->dropDownList($model, "Select a Category", '', \app\models\donationModel::getAllSubcategories(), 'filterCategory');
$filterForm::end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sortForm = \app\core\components\form\form::begin('', '');
$sortForm->checkBox($model,"Date","",'sortDate');
$sortForm->checkBox($model, "Amount", "amount", 'sortAmount');
$sortForm::end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$donationBtn = \app\core\components\form\form::begin('./CCdonations/create', 'get');

$donationBtn->end();

$searchDiv->end(); ?>

<div class="content">

    <div class="card-container" id="ongoingDonations">
    <?php
    //    echo "<pre>";
    //    print_r($ongoingDonations);
    //    echo "</pre>";

    $donationCards = new \app\core\components\cards\donationCard();

    $donationCards->displayCards($ongoingDonations);
    ?>
</div>

<div class="card-container" id="completedDonations" style="display: none">
    <?php
    //    echo "<pre>";
    //    print_r($completedDonations);
    //    echo "</pre>";

    $donationCards->displayCards($completedDonations);
    ?>
</div>

</div>



<script type="module" src="../public/JS/logistic/donations/view.js"></script>
