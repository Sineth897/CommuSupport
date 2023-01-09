<?php
/** @var $cho \app\models\choModel */
/** @var $user \app\models\userModel */
?>

<?php
echo $_SESSION['user'];
echo '<pre>';
if(empty(\app\core\Application::session()->getFlash('success'))) {
    print_r(\app\core\Application::session()->getFlash('error'));
} else if(empty(\app\core\Application::session()->getFlash('error'))) {
    print_r(\app\core\Application::session()->getFlash('success'));
}
echo '</pre>';
?>

<?php $choRegistrationForm = \app\core\components\form\form::begin('','post') ?>

<?php $choRegistrationForm->dropDownList($cho, "District",'district',$cho->getDistricts()) ?>

<?php $choRegistrationForm->inputField($cho,"Contact Number",'text','contactNumber') ?>

<?php $choRegistrationForm->inputField($cho,"Email",'text','email') ?>

<?php $choRegistrationForm->inputField($cho,"Address",'text','address') ?>

<?php $choRegistrationForm->inputField($user, "Username",'text','username') ?>

<?php $choRegistrationForm->inputField($user, "Password",'password','password') ?>

<?php $choRegistrationForm->submitButton("Confirm") ?>

<?php $choRegistrationForm->end() ?>


