<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../../public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="../../public/CSS/flashMessages.css">

<?php
/** @var $logistic \app\models\logisticModel */
/** @var $user \app\models\userModel */
?>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Register a Logistic Manager");

$headerDiv->end();

?>
<div class="content-form">

<?php $logisticRegistrationForm = \app\core\components\form\form::begin('','post') ?>

<div class="form-box">
    <?php $logisticRegistrationForm->formHeader("Logistic Manager Details") ?>

    <div>
        <?php $logisticRegistrationForm->inputField($logistic,"Name",'text','name') ?>

        <?php $logisticRegistrationForm->inputField($logistic,"Age",'text','age') ?>

        <?php $logisticRegistrationForm->inputField($logistic,"NIC",'text','NIC') ?>

        <?php $logisticRegistrationForm->inputField($logistic,"Gender",'text','gender') ?>

        <?php $logisticRegistrationForm->inputField($logistic, "Address",'text','address') ?>

        <?php $logisticRegistrationForm->inputField($logistic, "Contact Number",'text','contactNumber') ?>

    </div>

    <div class="form-split">
        <?php $logisticRegistrationForm->button("Confirm",'submit','confirm') ?>
    </div>


</div>

<?php $logisticRegistrationForm->end() ?>

</div>