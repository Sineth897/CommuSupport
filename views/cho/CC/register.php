<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<?php
/** @var $cc \app\models\ccModel */
/** @var $user \app\models\userModel */

?>


<!--        Profile Details-->
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

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<div class="heading-pages">
    <div class="heading">
        <h1>Create a Community Head Office</h1>
    </div>
</div>

<div class="content">

<?php $ccRegistrationForm = \app\core\components\form\form::begin('','post') ?>
<div class="login-grid-2">
    <div class="form-split">
        <?php $ccRegistrationForm->inputField($cc,"Address",'text','address') ?>

        <?php $ccRegistrationForm->inputField($cc,"City",'text','city') ?>

        <?php $ccRegistrationForm->inputField($cc,"Email",'email','email') ?>

        <?php $ccRegistrationForm->inputField($cc,"Fax",'text','fax')?>

        <?php $ccRegistrationForm->inputField($cc,"ContactNumber",'text','contactNumber')?>

    </div>
    <div class="form-split">


        <?php $ccRegistrationForm->inputField($user, "Username",'text','username') ?>

        <?php $ccRegistrationForm->inputField($user, "Password",'password','password') ?>

    </div>
    <br>
    <div >
        <?php $ccRegistrationForm->button("Confirm") ?>
    </div>

</div>

<?php $ccRegistrationForm->end() ?>
</div>
