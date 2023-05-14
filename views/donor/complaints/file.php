<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="./public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="./public/CSS/flashMessages.css">
<link href="../public/CSS/table/table-styles.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="../public/CSS/form/form.css" type="text/css" rel="stylesheet">
<style>
    .form-group{
        width: 800px;
    }
    .
</style>
<?php

/** @var $complaint \app\models\complaintModel */
/** @var $user \app\models\donorModel */
/** @var $model donationModel */

$donations = $model->getDonationDetails($_GET['processID']);

?>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Submit a complaint");

$headerDiv->end();
?>





<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();
$profile->profile();
$profile->end();
?>




<div class="content-form">

    <?php $complaintRegistrationForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-box">
  <div class="form-group">
      <label for="" class="form-label">
          Item
      </label>
      <input  class="basic-input-field" type="text" value="<?php echo $donations['subcategoryName']?>" disabled>

  </div>


        <div class="form-group">
            <label for="" class="form-label">
                Amount
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $donations['amount']?>" disabled>

        </div>

        <div class="form-group">
            <label for="" class="form-label">
                Date
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $donations['date']?>" disabled>

        </div>
        <div class="form-group">
            <label for="" class="form-label">
                Delivery Status
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $donations['deliveryStatus']?>" disabled>

        </div>
        <div class="form-group">
            <label for="" class="form-label">
                Driver Name
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $donations['name']?>" disabled>

        </div>

<div>


            <?php $complaintRegistrationForm->textArea($complaint,"Please provide the complaint" ,"complaint") ?>

</div>
            <div style="display:none;">

                <?php $complaintRegistrationForm->inputField($complaint,'Subject','text','subject') ?>
            </div>



        </div>

        <div style="padding: 2rem;display:flex;justify-content: center;">
            <?php $complaintRegistrationForm->button("Submit",'submit','confirm') ?>
        </div>



    </div>

    <?php $complaintRegistrationForm->end() ?>




</div>
