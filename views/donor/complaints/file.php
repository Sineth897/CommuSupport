<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="./public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="./public/CSS/flashMessages.css">

<?php

/** @var $complaint \app\models\complaintModel */
/** @var $user \app\models\donorModel */


?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("File a complaint");

$headerDiv->end();
?>



<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>


<div class="content-form">

    <?php $complaintRegistrationForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-box">



        <div >


            <?php $complaintRegistrationForm->textArea($complaint,"Please provide the complaint" ,"complaint") ?>

            <?php $complaintRegistrationForm->inputField($complaint,'Subject','text','subject') ?>


        </div>

        <div style="padding: 2rem;display:flex;justify-content: center">
            <?php $complaintRegistrationForm->button("File",'submit','confirm') ?>
        </div>



    </div>

    <?php $complaintRegistrationForm->end() ?>

</div>
