<?php

/** @var $driver \app\models\driverModel */
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

<?php $driverRegistrationForm = \app\core\components\form\form::begin('','post') ?>

<?php $driverRegistrationForm->inputField($driver,"Name",'text','name') ?>

<?php $driverRegistrationForm->inputField($driver,"Age",'text','age') ?>

<?php $driverRegistrationForm->inputField($driver,"NIC",'text','NIC') ?>

<?php $driverRegistrationForm->inputField($driver,"Gender",'text','gender')?>

<?php $driverRegistrationForm->inputField($driver,"Address",'text','address')?>

<?php $driverRegistrationForm->inputField($driver,"ContactNumber",'text','contactNumber')?>

<?php $driverRegistrationForm->inputField($driver,"LicenseNo",'text','licenseNo')?>

<?php $driverRegistrationForm->inputField($driver,"VehicleNo",'text','vehicleNo')?>

<?php $driverRegistrationForm->inputField($driver,"VehicleType",'text','vehicleType')?>

<?php $driverRegistrationForm->inputField($driver,"Preference",'text','preference')?>

<?php $driverRegistrationForm->inputField($user, "username",'text','username') ?>

<?php $driverRegistrationForm->inputField($user, "Password",'password','password') ?>

<?php $driverRegistrationForm->button("Confirm") ?>

<?php $driverRegistrationForm->end() ?>

