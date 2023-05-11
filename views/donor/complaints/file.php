<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="./public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="./public/CSS/flashMessages.css">

<?php

/** @var $complaint \app\models\complaintModel */
/** @var $user \app\models\donorModel */
/** @var $model \app\models\donationModel */


?>





<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();
$profile->profile();
$profile->end();
?>


<!--<div class="content" id="activeDonations">-->
<!---->
<!--    --><?php
//    foreach ($activeDonations as $donation) {
//        echo "<pre>";
//        echo "<p>Donation ID : {$donation['donationID']}</p>";
//        echo "<p>Item : {$donation['item']}</p>";
//        echo "<p>Amount : {$donation['amount']}</p>";
//        echo "<p>Delivery : {$donation['deliveryStatus']}</p>";
//        echo "<button id='{$donation['donationID']}' class='donation-view-btn vtn- primary'>View</button>";
//        echo "</pre>";
//    }
//    ?>
<!--</div>-->


<div class="content-form">

    <?php $complaintRegistrationForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-box">



        <div >


            <?php $complaintRegistrationForm->textArea($complaint,"Please provide the complaint" ,"complaint") ?>

            <div style="display:none;">

                <?php $complaintRegistrationForm->inputField($complaint,'Subject','text','subject') ?>
            </div>



        </div>

        <div style="padding: 2rem;display:flex;justify-content: center">
            <?php $complaintRegistrationForm->button("Submit",'submit','confirm') ?>
        </div>



    </div>

    <?php $complaintRegistrationForm->end() ?>

</div>
