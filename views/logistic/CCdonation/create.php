<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<?php

/** @var $model \app\models\ccDonationModel */

$categories = \app\models\ccDonationModel::getCategories();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Request from another Community Center"); ?>

<?php $headerDiv->end(); ?>

<div class="content">

    <?php $donationForm = \app\core\components\form\form::begin('', 'post'); ?>

    <?php $donationForm->formHeader('Item details'); ?>

    <div class="form-split">

        <?php $donationForm->dropDownList($model,"What is the category of the donation?","category",$categories,'category'); ?>

        <?php foreach ($categories as $category => $name)  {?>

            <div id='<?php echo $category ?>' style='display: none'>
                <?php $donationForm->dropDownList($model, 'What item you need', 'item', $model->getSubcategories($category)); ?>
            </div>
        <?php } ?>

        <div id="amountInput">
            <?php $donationForm->inputField($model,"Amount",'number','amount',); ?>
        </div>


    </div>

    <?php $donationForm->formHeader('Donation Details'); ?>

    <?php $donationForm->textArea($model,"Please specify the reason for the request","notes"); ?>

    <?php $donationForm->button("Request","submit"); ?>

</div>

<script type="module" src="../../public/JS/logistic/CCdonation/create.js"></script>

<script>

    <?php if(!empty($_POST['category'])) { ?>
    document.getElementById('<?php echo $_POST['category'] ?>').style.display = 'block';
    document.getElementById('category').value = '<?php echo $_POST['category'] ?>';
    <?php } ?>

</script>