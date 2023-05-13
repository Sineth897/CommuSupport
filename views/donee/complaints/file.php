<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="./public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="./public/CSS/flashMessages.css">
<link rel="stylesheet" href="../public/CSS/form/form.css" type="text/css" rel="stylesheet">

<?php

/** @var $complaint \app\models\complaintModel */
/** @var $user \app\models\doneeModel */

$requests = $complaint->requestComplaints($_GET['processID']);

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

<div class="content-form">

    <?php $complaintRegistrationForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-box">
        <div class="form-group">
            <label for="" class="form-label">
                Notes
            </label>
            <input  class="basic-input-field" type="text" value="<?php echo $requests[0]['notes']?>" disabled>

        </div>
        <div class="form-group">
            <label for="" class="form-label">
                Item
            </label>
            <input  class="basic-input-field" type="text" value="<?php echo $requests[0]['item']?>" disabled>

        </div>


        <div class="form-group">
            <label for="" class="form-label">
                Amount
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $requests[0]['amount']?>" disabled>

        </div>

        <div class="form-group">
            <label for="" class="form-label">
                Urgency
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $requests[0]['urgency']?>" disabled>

        </div>
        <div class="form-group">
            <label for="" class="form-label">
                Posted Date
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $requests[0]['postedDate']?>" disabled>

        </div>
        <div class="form-group">
            <label for="" class="form-label">
                Request expire date
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $requests[0]['expDate']?>" disabled>

        </div>




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
