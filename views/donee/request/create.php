<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<?php

/** @var $requestmodel \app\models\requestModel */

$categories = $requestmodel->getCategories();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Post a request");

$headerDiv->end();
?>

<?php
$checkVerification = new \app\core\components\layout\verificationDiv();

if($checkVerification->notVerified()) {
    return;
}
?>

<div class="content-form">

    <div class="form-box">

    <?php $donationForm = \app\core\components\form\form::begin('','post') ?>

    <?php $donationForm->formHeader("Request your need"); ?>

    <div class="form-split">

        <?php $donationForm->dropDownList($requestmodel, 'What is the category of needed item', 'requestCategoryID', $categories,'requestCategory'); ?>

        <?php foreach ($categories as $category => $name)  {?>

        <div id='<?php echo $category ?>' style='display: none'>
        <?php $donationForm->dropDownList($requestmodel, 'What item you need', 'item', $requestmodel->getSubcategories($category)); ?>
        </div>
    <?php } ?>
    </div>

    <div style="display: none" id="amountInput" class="form-split">
        <?php $donationForm->inputField($requestmodel,'Amount','number','amount',); ?>
    </div>


    <?php $donationForm->formHeader('Delivery Details'); ?>

    <?php $donationForm->inputField($requestmodel,'Delivery Address (Optional)','text','address'); ?>

    <div class="form-split">
        <?php $donationForm->dropDownList($requestmodel,'Urgency','urgency',$requestmodel->getUrgency()); ?>
    </div>

    <?php $donationForm->textArea($requestmodel,"Additional Notes (optional)",'notes') ?>

    <?php $donationForm->button('Create'); ?>

    <?php $donationForm::end();?>

    </div>

</div>

<script type="module" src="../../public/JS/donee/request/create.js"></script>

<script>

    <?php if(isset($_POST['requestCategoryID'])) { ?>
        document.getElementById('<?php echo $_POST['requestCategoryID'] ?>').style.display = 'block';
        document.getElementById('requestCategory').value = '<?php echo $_POST['requestCategoryID'] ?>';
    <?php } ?>

</script>