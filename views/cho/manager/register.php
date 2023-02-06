<?php
/** @var $manager \app\models\managerModel */
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

<?php $managerRegistrationForm = \app\core\components\form\form::begin('','post') ?>

<?php $managerRegistrationForm->inputField($manager,"Name",'text','name') ?>

<?php $managerRegistrationForm->inputField($manager,"Age",'text','age') ?>

<?php $managerRegistrationForm->inputField($manager,"NIC",'text','NIC') ?>

<?php $managerRegistrationForm->inputField($manager,"Gender",'text','gender') ?>

<?php $managerRegistrationForm->inputField($manager, "Address",'text','address') ?>

<?php $managerRegistrationForm->inputField($manager, "Contact Number",'text','contactNumber') ?>

<?php $managerRegistrationForm->button("Confirm") ?>

<?php $managerRegistrationForm->end() ?>
