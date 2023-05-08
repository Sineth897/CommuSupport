<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/cards/cc-donation-card.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/**
 * @var $model \app\models\ccdonationModel
 * @var $user \app\models\logisticModel
 */

//get the logistic officers details
$user = $user->findOne(['employeeID' => $_SESSION['user']]);

//retrieve all donations between community centers  and community centers' details as a key value pair array
$donations = $model->getDonations();

//based on community centers IDs in the donations table, get the community centers details (city)
$donations = array_map(function ($donation) use ($donations) {
    if(!empty($donation['fromCC'])) {
        $donation['fromCity'] = $donations['CCs'][$donation['fromCC']];
    }
    $donation['toCity'] = $donations['CCs'][$donation['toCC']];
    return $donation;
}, $donations['donations']);

//filter posted donations from all donation list which are not accepted by a community center yet
//conditions are fromCC column should be empty and toCC column should not be the current user's community center
$postedDonations = array_filter($donations, function ($donation) use ($user) {
    return empty($donation['fromCC']) && $donation['toCC'] !== $user->ccID;
});

//filter ongoing donations from all donation list which are accepted by a community center but not completed or posted by the user's community center yet
//conditions are fromCC or toCC column must be user's community center and completedDate column should be empty
$ongoingDonations = array_filter($donations, function ($donation) use ($user) {
    return ($donation['toCC'] === $user->ccID || $donation['fromCC'] === $user->ccID) && empty($donation['completedDate']) ;
});

//filter completed donations from all donation list which are completed and user's community center is either fromCC or toCC
//conditions are fromCC or toCC column must be user's community center and completedDate column should not be empty
$completedDonations = array_filter($donations, function ($donation) use ($user) {
    return ($donation['toCC'] === $user->ccID || $donation['fromCC'] === $user->ccID) && !empty($donation['completedDate']);
});

?>

<!--This will show the profile with the link to the profile and notification the user has received-->
<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<!--This will show the header with the heading and the pages-->
<?php $headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Donations");

$headerDiv->pages(["posted","ongoing","completed"]);

$headerDiv->end(); ?>

<!--This will show the search bar and the filter and sort options-->
<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

// filter options
$filterForm = \app\core\components\form\form::begin('', '');

// filter by type of the item requested
$filterForm->dropDownList($model, "Select a Category", '', \app\models\donationModel::getAllSubcategories(), 'filterCategory');
$filterForm::end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

// sort options
$sortForm = \app\core\components\form\form::begin('', '');

// sort by created date
$sortForm->checkBox($model,"Created Date","",'sortCreatedDate');

// sort by amount
$sortForm->checkBox($model, "Amount", "amount", 'sortAmount');
$sortForm::end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

//this form is to request a donation from another community center
$donationBtn = \app\core\components\form\form::begin('./CCdonations/create', 'get');

$donationBtn->button("Request from another", "submit");

$donationBtn->end();

$searchDiv->end(); ?>

<div class="content" >

    <div class="card-container" id="postedDonations">

        <?php

            $ccDonationCards = new \app\core\components\cards\ccDonationCard();

            //to show posted donation cards by all community centers
            $ccDonationCards->showCCDonationCards($postedDonations);
        ?>

    </div>

    <div class="card-container" id="ongoingDonations" style="display: none">

        <?php

        // to show cc donations which are posted by the user or accepted by the user
        $ccDonationCards->showCCDonationCards($ongoingDonations, 'ongoing',$user->ccID);

        ?>

    </div>

    <div class="card-container" id="completedDonations" style="display: none">

        <?php

        // to show cc donations which the delivery is completed and either user has posted or accepted
        $ccDonationCards->showCCDonationCards($completedDonations,'completed',$user->ccID);

        ?>

    </div>

</div>


<script type="module" src="../public/JS/logistic/CCdonation/view.js"></script>