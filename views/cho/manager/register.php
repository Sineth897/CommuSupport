<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../../public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="../../public/CSS/flashMessages.css">
<?php
/** @var $manager \app\models\managerModel */
/** @var $user \app\models\userModel */
/** @var $cc \app\models\ccModel */
?>


    <?php
    $headerDiv = new \app\core\components\layout\headerDiv();

    $headerDiv->heading("Register a Manager");

    $headerDiv->end();

    ?>
    <div class="content-form">

        <?php $managerRegistrationForm = \app\core\components\form\form::begin('','post')?>


    <div class="form-box">

        <?php $managerRegistrationForm->formHeader("Manager Details")?>

        <div>

            <?php $managerRegistrationForm->inputField($manager,"Name",'text','name') ?>

            <?php $managerRegistrationForm->inputField($manager,"Age",'text','age') ?>

            <?php $managerRegistrationForm->inputField($manager,"NIC",'text','NIC') ?>

            <?php $managerRegistrationForm->inputField($manager,"Gender",'text','gender') ?>

            <?php $managerRegistrationForm->inputField($manager, "Address",'text','address') ?>

            <?php $managerRegistrationForm->inputField($manager, "Contact Number",'text','contactNumber') ?>

            <?php $managerRegistrationForm->inputField($user,"User Name",'text','username')?>

            <?php $managerRegistrationForm->inputField($user,"Password",'password','password')?>

            <?php $managerRegistrationForm->inputField($user,"Confirm Password",'password','confirmPassword')?>

            <div style="display:none">
                <?php $managerRegistrationForm->inputField($manager,"CC ID ",'hidden','ccID')?>
            </div>

        </div>


    </div>
    <div class="form-split">
        <?php $managerRegistrationForm->button("Confirm",'submit','confirm') ?>
    </div>

</div>

<?php $managerRegistrationForm->end() ?>

</div>
