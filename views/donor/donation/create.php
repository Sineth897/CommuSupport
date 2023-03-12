<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<?php

/** @var $model \app\models\donationModel */
/** @var  $user \app\models\userModel */

$CHOs = \app\models\choModel::getCHOs();
$categories = $model->getCategories();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Donate to a community center");

$headerDiv->end();
?>

<?php
$checkVerification = new \app\core\components\layout\verificationDiv();

if($checkVerification->notVerified()) {
    return;
}
?>

<?php $donationForm = \app\core\components\form\form::begin('','post') ?>

<?php //$donationForm->formHeader('Select to donate any Community Center other than your own') ?>
<!---->
<!--<div class="form-split">-->
<!--        --><?php //$donationForm->dropDownList($model,'Select a district','district',$CHOs,'district') ?>
<!---->
<!--        --><?php //foreach ($CHOs as $key => $value) : ?>
<!--            <div id="--><?php //echo $key ?><!--" style="display: none">-->
<!--                --><?php //$donationForm->dropDownList($model,'Choose City','donateTo',\app\models\choModel::getCCsUnderCHO($key)); ?>
<!--            </div>-->
<!--        --><?php //endforeach; ?>
<!--</div>-->

<?php $donationForm->formHeader('Enter donation details') ?>

<div class="form-split">

    <?php $donationForm->dropDownList($model,"Select item category",'category',$categories,'category'); ?>

    <?php foreach ($categories as $category => $name)  {?>
        <div id=<?php echo $category ?> style="display: none">
            <?php $donationForm->dropDownList($model, 'What item you need', 'item', $model->getSubcategories($category)); ?>
        </div>
    <?php } ?>

    <div id="amountInput" style="display: none">
        <?php $donationForm->inputField($model, 'Amount','number','amount',); ?>
    </div>

<!--</div>-->

<?php //$donationForm->formHeader('Delivery pickup Details') ?>
<!---->
<!--<p> If pickup is place other than your address please specify.</p>-->
<!---->
<?php //$donationForm->inputField($model,'Delivery Address (Optional)','text','address'); ?>
<!---->
<?php //$donationForm->button('Submit','submit','btn btn-primary'); ?>
<!---->
<?php //$donationForm::end(); ?>


<script type="module" src="../../public/JS/donor/donation/create.js"></script>

<script>
<!--    --><?php
//        if(!empty($_POST['district'])) { ?>
//            document.getElementById('district').value = '<?php //echo $_POST['district'] ?>//';
//            document.getElementById('<?php //echo $_POST['district'] ?>//').style.display = 'block';
//            (document.getElementById('<?php //echo $_POST['district'] ?>//').getElementsByTagName('select')[0]).value = '<?php //echo $_POST['donateTo'] ?>//';
//    <?php //}?>

    <?php
        if(!empty($_POST['category'])) { ?>
            document.getElementById('category').value = '<?php echo $_POST['category'] ?>';
            document.getElementById('<?php echo $_POST['category'] ?>').style.display = 'block';
            (document.getElementById('<?php echo $_POST['category'] ?>').getElementsByTagName('select')[0]).value = '<?php echo $_POST['item'] ?>';
    <?php }?>

</script>