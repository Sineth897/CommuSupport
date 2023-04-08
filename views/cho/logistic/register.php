<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<?php
/** @var $logistic \app\models\logisticModel */
/** @var $user \app\models\userModel */
?>

<?php $logisticRegistrationForm = \app\core\components\form\form::begin('','post') ?>
<div class="login-grid-2">
    <div class="form-split">
        <?php $logisticRegistrationForm->inputField($logistic,"Name",'text','name') ?>

        <?php $logisticRegistrationForm->inputField($logistic,"Age",'text','age') ?>

        <?php $logisticRegistrationForm->inputField($logistic,"NIC",'text','NIC') ?>

        <?php $logisticRegistrationForm->inputField($logistic,"Gender",'text','gender') ?>

        <?php $logisticRegistrationForm->inputField($logistic, "Address",'text','address') ?>

        <?php $logisticRegistrationForm->inputField($logistic, "Contact Number",'text','contactNumber') ?>

    </div>

    <div class="form-split">
        <?php $logisticRegistrationForm->button("Confirm") ?>
    </div>


</div>

<?php $logisticRegistrationForm->end() ?>
