<?php
    /** @var $driver \app\models\donerModel */
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

<?php $donerRegistrationForm = \app\core\components\form\form::begin('','post') ?>

<div>
    <?php $donerRegistrationForm->inputField($doner, "Doner ID",'text','donerID') ?>
</div>

<div>
    <?php $donerRegistrationForm->inputField($doner, "First name",'text','fname') ?>
</div>

<div>
    <?php $donerRegistrationForm->inputField($doner, "Last name",'text','lname') ?>
</div>

<div>
    <?php $donerRegistrationForm->dropDownList($doner,'NIC','text','NIC') ?>
</div>

<div>
    <?php $donerRegistrationForm->inputField($doner, "Age",'number','age') ?>
</div>

<div>
    <?php $donerRegistrationForm->inputField($doner, "Contact Number",'text','contactNumber') ?>
</div>

<div>
    <?php $donerRegistrationForm->inputField($user, "Username",'text','username') ?>
</div>

<div>
    <?php $donerRegistrationForm->inputField($user, "Password",'password','password') ?>
</div>


<?php $donerRegistrationForm->submitButton('Register') ?>

<?php $donerRegistrationForm::end();?>

