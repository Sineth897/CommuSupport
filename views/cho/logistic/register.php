<?php
/** @var $logistic \app\models\logisticModel */
/** @var $user \app\models\userModel */
?>

<?php
echo $_SESSION=['user'];
echo "<pre>";
if(empty(\app\core\Application::session()->getFlash('success'))){
    print_r(\app\core\Application::session()->getFlash('error'));
}else if (empty(\app\core\Application::session()->getFlash('error'))){
    print_r(\app\core\Application::session()->getFlash('success'));
}
echo '</pre>';
?>

<?php $logisticRegistrationForm = \app\core\components\form\form::begin('','post') ?>

<?php $logisticRegistrationForm->inputField($logistic,"Name",'text','name') ?>

<?php $logisticRegistrationForm->inputField($logistic,"Age",'text','age') ?>

<?php $logisticRegistrationForm->inputField($logistic,"NIC",'text','NIC') ?>

<?php $logisticRegistrationForm->inputField($logistic,"Gender",'text','gender') ?>

<?php $logisticRegistrationForm->inputField($logistic, "Address",'text','address') ?>

<?php $logisticRegistrationForm->inputField($logistic, "Contact Number",'text','contactNumber') ?>

<?php $logisticRegistrationForm->button("Confirm") ?>

<?php $logisticRegistrationForm->end() ?>
