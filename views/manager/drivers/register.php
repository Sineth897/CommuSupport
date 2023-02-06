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

<div>
    <?php $driverRegistrationForm->inputField($driver, "Driver's name",'text','name') ?>
</div>

<div>
    <?php $driverRegistrationForm->inputField($driver, "Age",'number','age') ?>
</div>

<div>
    <?php $driverRegistrationForm->inputField($driver, "NIC",'text','NIC') ?>
</div>

<div>
    <?php $driverRegistrationForm->dropDownList($driver,'Gender','gender',$user->getGenders()); ?>
</div>

<div>
    <?php $driverRegistrationForm->inputField($driver, "Address",'text','address') ?>
</div>

<div>
    <?php $driverRegistrationForm->inputField($driver, "Contact Number",'text','contactNumber') ?>
</div>

<div>
    <?php $driverRegistrationForm->inputField($driver, "License Number",'text','licenseNo') ?>
</div>

<div>
    <?php $driverRegistrationForm->inputField($driver, "Vehicle Number",'text','vehicleNo') ?>
</div>

<div>
    <?php $driverRegistrationForm->dropDownList($driver, "Vehicle Type",'vehicleType',$driver->getVehicleTypes()) ?>
</div>

<div>
    <?php $driverRegistrationForm->dropDownList($driver, "Delivery preference",'preference',$driver->getPreferences()) ?>
</div>

<div>
    <?php $driverRegistrationForm->inputField($user, "Username",'text','username') ?>
</div>

<div>
    <?php $driverRegistrationForm->inputField($user, "Password",'password','password') ?>
</div>


<?php $driverRegistrationForm->button('Register') ?>

<?php $driverRegistrationForm::end();?>

