<?php

/** @var $cc \app\models\ccModel */
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

<?php $ccRegistrationForm = \app\core\components\form\form::begin('','post') ?>

<?php $ccRegistrationForm->inputField($cc,"Address",'text','address') ?>

<?php $ccRegistrationForm->inputField($cc,"City",'text','city') ?>

<?php $ccRegistrationForm->inputField($cc,"Email",'email','email') ?>


<?php $ccRegistrationForm->inputField($cc,"Fax",'text','fax')?>

<?php $ccRegistrationForm->inputField($cc,"ContactNumber",'text','contactNumber')?>

<?php $ccRegistrationForm->inputField($user, "username",'text','username') ?>

<?php $ccRegistrationForm->inputField($user, "Password",'password','password') ?>

<?php $ccRegistrationForm->button("Confirm") ?>

<?php $ccRegistrationForm->end() ?>
