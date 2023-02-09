<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">

<?php
    /** @var $driver \app\models\driverModel */
    /** @var $user \app\models\userModel */
?>

<?php
    echo '<pre>';
    if(empty(\app\core\Application::session()->getFlash('success'))) {
        print_r(\app\core\Application::session()->getFlash('error'));
    } else if(empty(\app\core\Application::session()->getFlash('error'))) {
        print_r(\app\core\Application::session()->getFlash('success'));
    }
    echo '</pre>';
?>

<div class="profile">
    <div class="notif-box">
        <i class="material-icons">notifications</i>
    </div>
    <div class="profile-box">
        <div class="name-box">
            <h4>Username</h4>
            <p>Position</p>
        </div>
        <div class="profile-img">
            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile">
        </div>
    </div>
</div>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Register a Driver");



$headerDiv->end();
?>



<?php $driverRegistrationForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-split" >

        <div id="driverDetails">

            <div class='heading'>
                <h1>Driver Details</h1>
            </div>

            <?php $driverRegistrationForm->inputField($driver, "Driver's name",'text','name') ?>

            <?php $driverRegistrationForm->inputField($driver, "Age",'number','age') ?>

            <?php $driverRegistrationForm->dropDownList($driver,'Gender','gender',$user->getGenders()); ?>

            <?php $driverRegistrationForm->inputField($driver, "NIC",'text','NIC') ?>

            <?php $driverRegistrationForm->inputField($driver, "License Number",'text','licenseNo') ?>

            <?php $driverRegistrationForm->inputField($driver, "Address",'text','address') ?>

            <?php $driverRegistrationForm->inputField($driver, "Contact Number",'text','contactNumber') ?>
        </div>

        <div id="vehicleDetails">

            <div class='heading'>
                <h1>Vehicle Details</h1>
            </div>

            <?php $driverRegistrationForm->inputField($driver, "Vehicle Number",'text','vehicleNo') ?>

            <?php $driverRegistrationForm->dropDownList($driver, "Vehicle Type",'vehicleType',$driver->getVehicleTypes()) ?>

            <?php $driverRegistrationForm->dropDownList($driver, "Delivery preference",'preference',$driver->getPreferences()) ?>

            <div class='heading'>
                <h1>User Profile</h1>
            </div>

            <?php $driverRegistrationForm->inputField($user, "Username",'text','username') ?>

            <?php $driverRegistrationForm->inputField($user, "Password",'password','password') ?>

            <?php $driverRegistrationForm->button('Register') ?>
        </div>

    </div>

<?php $driverRegistrationForm::end();?>

