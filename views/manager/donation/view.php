<link rel="stylesheet" href="/CommuSupport/public/CSS/cards/donor-donation-card.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<?php

/** @var $model \app\models\donationModel */
/** @var $user \app\models\managerModel */

use app\core\Application;

$user = $user->findOne(['employeeID' => Application::$app->session->get('user')]);

$donations = $model->retrieveDonationsForManager($user->ccID);
//$donations = $model->retrieve();

$ongoingDonations = array_filter($donations, function($donation) {
    return $donation['deliveryStatus'] === "Not Assigned" || $donation['deliveryStatus'] === "Ongoing";
});

$completedDonations = array_filter($donations, function($donation) {
    return $donation['deliveryStatus'] === "Completed";
});

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Donations");

$headerDiv->pages(["ongoing","completed"]);

$headerDiv->end();
?>

<!--        Search and filter boxes -->
<?php
$searchDiv = new \app\core\components\layout\searchDiv();

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

$searchDiv->end();
?>

<div class="content">

    <div class="card-container" id="ongoingDonations">

        <?php

//        echo "<pre>";
//        print_r($donations);
//        echo "</pre>";

        $donationCards = new \app\core\components\cards\donationCard();

        $donationCards->displayCards($ongoingDonations);

        ?>

    </div>

    <div class="card-container" id="completedDonations" style="display: none">

        <?php

        $donationCards = new \app\core\components\cards\donationCard();

        $donationCards->displayCards($completedDonations);

        ?>

    </div>

</div>

<script type="module" src="/CommuSupport/public/JS/manager/donations/view.js"></script>
