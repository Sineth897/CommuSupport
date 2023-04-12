<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../../public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="../../public/CSS/flashMessages.css">
<?php
/** @var $manager \app\models\managerModel */
/** @var $user \app\models\userModel */
?>


    <?php
    $headerDiv = new \app\core\components\layout\headerDiv();

    $headerDiv->heading("Register a Manager");

    $headerDiv->end();

    ?>
    <div class="content-form">

        <?php $managerRegistrationForm = \app\core\components\form\form::begin('','post')?>


    <div class="form-box">

        <?php $managerRegistrationForm->formHeader("Manger Details")?>

        <div>

            <?php $managerRegistrationForm->inputField($manager,"Name",'text','name') ?>

            <?php $managerRegistrationForm->inputField($manager,"Age",'text','age') ?>

            <?php $managerRegistrationForm->inputField($manager,"NIC",'text','NIC') ?>

            <?php $managerRegistrationForm->inputField($manager,"Gender",'text','gender') ?>

            <?php $managerRegistrationForm->inputField($manager, "Address",'text','address') ?>

            <?php $managerRegistrationForm->inputField($manager, "Contact Number",'text','contactNumber') ?>


        </div>


    </div>
    <div class="form-split">
        <?php $managerRegistrationForm->button("Confirm",'submit','confirm') ?>
    </div>

</div>

<?php $managerRegistrationForm->end() ?>

</div>
