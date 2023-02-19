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

<div class="content-form">

    <div class="form-box">

    <?php $requestForm = \app\core\components\form\form::begin('','post') ?>

    <?php $requestForm->formHeader("Request your need"); ?>

    <div class="form-split">

        <?php $requestForm->dropDownList($requestmodel, 'What is the category of needed item', 'requestCategoryID', $categories,'requestCategory'); ?>

        <?php foreach ($categories as $category => $name)  {?>

        <div id='<?php echo $category ?>' style='display: none'>
        <?php $requestForm->dropDownList($requestmodel, 'What item you need', 'item', $requestmodel->getSubcategories($category)); ?>
        </div>
    <?php } ?>
    </div>

    <div style="display: none" id="amountInput" class="form-split">
        <?php $requestForm->inputField($requestmodel,'Amount','number','amount',); ?>
    </div>


    <?php $requestForm->formHeader('Delivery Details'); ?>

    <?php $requestForm->inputField($requestmodel,'Delivery Address (Optional)','text','address'); ?>

    <div class="form-split">
        <?php $requestForm->dropDownList($requestmodel,'Urgency','urgency',$requestmodel->getUrgency()); ?>
    </div>

    <?php $requestForm->textArea($requestmodel,"Additional Notes (optional)",'notes') ?>

    <?php $requestForm->button('Create'); ?>

    <?php $requestForm::end();?>

    </div>

</div>

<script type="module" src="../../public/JS/donee/request/create.js"></script>

<script>

    <?php if(isset($_POST['requestCategoryID'])) { ?>
        document.getElementById('<?php echo $_POST['requestCategoryID'] ?>').style.display = 'block';
        document.getElementById('requestCategory').value = '<?php echo $_POST['requestCategoryID'] ?>';
    <?php } ?>

</script>