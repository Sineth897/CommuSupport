<link rel="stylesheet" href="/CommuSupport/public/CSS/cards/donor-donation-card.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">

<?php

/** @var $model \app\models\donationModel */
/** @var  $user \app\models\donorModel */

$categories = $model->getCategories();

//$user = $user->findOne(['donorID' => $_SESSION['user']]);

$donations = $model->getDonationsFromDonorsToViewByDonors($_SESSION['user']);

$activeDonations = array_filter($donations, function($donation) {
    return $donation['deliveryStatus'] === 'Ongoing' || $donation['deliveryStatus'] === 'Not assigned';
});

$completedDonations = array_filter($donations, function($donation) {
    return $donation['deliveryStatus'] === 'Completed';
});

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();;

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


echo "<button class='btn-cta-primary' type='button' id='createDonation'> Donate </button>";


$searchDiv->end();
?>


<div class="content">

    <div class="card-container" id="activeDonations">

        <?php $donationCards = new \app\core\components\cards\donationCard();

        $donationCards->displayCards($activeDonations); ?>


    </div>

    <div class="card-container" id="completedDonations" style="display: none">

        <?php $donationCards->displayCards($completedDonations); ?>

    </div>

</div>



<div class="popup-background" id="donationDiv" style="position: fixed">

    <div class="popup">

        <?php $donationForm = \app\core\components\form\form::begin('','post') ?>

        <?php $donationForm->formHeader('Enter donation details') ?>

        <div class="form-grid-2-2">


            <?php $donationForm->dropDownList($model,"Select item category",'category',$categories,'category'); ?>

            <?php foreach ($categories as $category => $name)  {?>
                <div id=<?php echo $category ?> class="form-group" style="display: none;">
                <?php $donationForm->dropDownList($model, 'What item you will donate', 'item', $model->getSubcategories($category)); ?>
                </div>
            <?php } ?>

            <div id="amountInput" style="display: none">
                <?php $donationForm->inputField($model, 'Amount','number','amount','amount'); ?>
            </div>

        </div>

        <div class="form-split">
            <?php $donationForm->button('Confirm','button','confirmDonation',['btn-primary']); ?>

            <?php $donationForm->button('Cancel','button','cancelDonation',['btn-secondary']); ?>
        </div>


        <?php $donationForm::end(); ?>

    </div>

</div>

<script type="module" src="../public/JS/donor/donation/view.js"></script>
<script type="module" src="../public/JS/donor/donation/create.js"></script>