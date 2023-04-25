<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="./public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="./public/CSS/flashMessages.css">

<?php

/** @var $complaint \app\models\complaintModel */
/** @var $user \app\models\userModel */

?>


<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("File a complaint");

$headerDiv->end();
?>

<div class="content-form">

    <?php $complaintRegistrationForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-box">

        <?php $complaintRegistrationForm ->formHeader('File a complaint ') ?>

        <div >

            <?php $complaintRegistrationForm->inputField($complaint,'Filed By','text','userID') ?>

            <?php $complaintRegistrationForm->inputField($complaint,'Filed Date','date','filedDate') ?>

            <?php $complaintRegistrationForm->inputField($complaint,'Subject','text','subject') ?>

            <?php $complaintRegistrationForm->inputField($complaint,'Status','text','status') ?>

            <?php $complaintRegistrationForm->inputField($complaint,"CHO ID ",'text','choID')?>


        </div>

        <div style="padding: 2rem;display:flex;justify-content: center">
            <?php $complaintRegistrationForm->button("File",'submit','confirm') ?>
        </div>



    </div>

    <?php $complaintRegistrationForm->end() ?>

</div>
