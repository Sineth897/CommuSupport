<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<?php
/** @var $manager \app\models\managerModel */
/** @var $user \app\models\userModel */
?>


<?php $managerRegistrationForm = \app\core\components\form\form::begin('','post') ?>
<div class="form-split">

    <div class="login-grid-2">
        <?php $managerRegistrationForm->inputField($manager,"Name",'text','name') ?>

        <?php $managerRegistrationForm->inputField($manager,"Age",'text','age') ?>

        <?php $managerRegistrationForm->inputField($manager,"NIC",'text','NIC') ?>

        <?php $managerRegistrationForm->inputField($manager,"Gender",'text','gender') ?>

        <?php $managerRegistrationForm->inputField($manager, "Address",'text','address') ?>

        <?php $managerRegistrationForm->inputField($manager, "Contact Number",'text','contactNumber') ?>

    </div>
    <div class="form-split">
        <?php $managerRegistrationForm->button("Confirm") ?>
    </div>

</div>

<?php $managerRegistrationForm->end() ?>
