<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="./public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="./public/CSS/flashMessages.css">

<?php

/** @var $complaint \app\models\complaintModel */
/** @var $user \app\models\donorModel */
/** @var $model \app\models\donationModel */
//
//
//$donationID= \app\core\Application::session('user');
//$donationDetails=$donation->getDonationDetails($donationID);

//
//$categories = $model->getCategories();
//
//$donations = $model->retrieve(['createdBy' => $_SESSION['user']]);
//
//$activeDonations = array_filter($donations, function($donation) {
//    return $donation['deliveryStatus'] === 'Ongoing' || $donation['deliveryStatus'] === 'Not assigned';
//});

?>

<?php

$profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end();

?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("File a complaint");

$headerDiv->end();
?>

<div>

</div>

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
