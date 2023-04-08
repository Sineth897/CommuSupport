<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $model \app\models\donationModel */
/** @var  $user \app\models\donorModel */

$categories = $model->getCategories();

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


echo "<button class='btn-cta-primary' type='button' id='createDonation'> Donate </button>";


$searchDiv->end();
?>


<div class="content" id="activeDonations">
    <h3>Ongoing Donations</h3>
</div>

<div class="content" id="completedDonations">
    <h3>Completed Donations</h3>
</div>

<div class="popup-background" id="donationDiv">

    <div class="popup">

        <?php $donationForm = \app\core\components\form\form::begin('','post') ?>

        <?php $donationForm->formHeader('Enter donation details') ?>

        <div class="form-grid-2-2">


            <?php $donationForm->dropDownList($model,"Select item category",'category',$categories,'category'); ?>

            <?php foreach ($categories as $category => $name)  {?>
                <div id=<?php echo $category ?> style="display: none">
                <?php $donationForm->dropDownList($model, 'What item you need', 'item', $model->getSubcategories($category)); ?>

            <?php } ?>

            <div id="amountInput" style="display: none">
                <?php $donationForm->inputField($model, 'Amount','number','amount',); ?>
            </div>

        </div>

        <?php $donationForm::end(); ?>

    </div>

</div>

<script type="module" src="../public/JS/donor/donation/view.js"></script>
<script type="module" src="../public/JS/donor/donation/create.js"></script>