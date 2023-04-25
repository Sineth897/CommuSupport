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

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Register a Driver");

$headerDiv->end();
?>



<?php $driverRegistrationForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-split" >

        <div id="driverDetails">

            <div class='heading'>
                <h3>Driver Details</h3>
            </div>

            <?php $driverRegistrationForm->inputField($driver, "Driver's name",'text','name') ?>

            <?php $driverRegistrationForm->inputField($driver, "Age",'number','age') ?>

            <?php $driverRegistrationForm->dropDownList($driver,'Gender','gender',$user->getGenders()); ?>

        </div>

        <div id="vehicleDetails">

            <div class='heading'>
                <h3>Vehicle Details</h3>
            </div>

            <?php $driverRegistrationForm->inputField($driver, "Vehicle Number",'text','vehicleNo') ?>

            <?php $driverRegistrationForm->dropDownList($driver, "Vehicle Type",'vehicleType',$driver->getVehicleTypes()) ?>

            <?php $driverRegistrationForm->dropDownList($driver, "Delivery preference",'preference',$driver->getPreferences()) ?>

        </div>


    </div>

    <div class="form-split">

        <?php $driverRegistrationForm->inputField($driver, "NIC",'text','NIC') ?>

        <div class='form-group'>
            <div class="heading">
                <h3>User Profile</h3>
            </div>
        </div>


    </div>

<div class="form-split">

    <div>
        <?php $driverRegistrationForm->inputField($driver, "License Number",'text','licenseNo') ?>

        <?php $driverRegistrationForm->inputField($driver, "Address",'text','address') ?>

        <?php $driverRegistrationForm->inputField($driver, "Contact Number",'text','contactNumber') ?>
    </div>

    <div>
        <?php $driverRegistrationForm->inputField($user, "Username",'text','username') ?>

        <?php $driverRegistrationForm->inputField($user, "Password",'password','password') ?>

    </div>

    <div>
        <?php $driverRegistrationForm->button('Register') ?>
    </div>


</div>



<?php $driverRegistrationForm::end();?>

