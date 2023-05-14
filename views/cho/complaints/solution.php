<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">


<?php
/** @var $solution \app\models\complaintModel */
/** @var $user \app\models\choModel */

//print_r($solutions);




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

$headerDiv->heading("Provide a solution");

$headerDiv->end();
?>


<div class="container">

</div>
<div class="content-form">
<!---->
    <?php $solutionForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-box">

        <div class="form-group">
            <label for="" class="form-label">
                Complaint
            </label>
            <input  class="basic-input-field" type="text" value="<?php echo $solutions[0]['complaint']?>" disabled>

        </div>
        <div class="form-group">
            <label for="" class="form-label">
                Complaint Filed By
            </label>
            <input  class="basic-input-field" type="text" value="<?php echo $solutions[0]['username']?>" disabled>

        </div>


        <div class="form-group">
            <label for="" class="form-label">
                Complaint Regarding
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $solutions[0]['sub']?>" disabled>

        </div>

        <div class="form-group">
            <label for="" class="form-label">
                Complaint Filed Date
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $solutions[0]['filedDate']?>" disabled>

        </div>
        <div class="form-group">
            <label for="" class="form-label">
                Status
            </label>
            <input class="basic-input-field" type="text" value="<?php echo $solutions[0]['status']?>" disabled>

        </div>



        <div >

            <?php $solutionForm ->textArea($solution,"Please provide the solution" ,"solution") ?>

        </div>

        <div style="display:none;">

            <?php $solutionForm->inputField($solution,'ComplaintID','text','complaintID') ?>
            <?php $solutionForm->inputField($solution,'FiledBy','text','filedBy') ?>
        </div>


        <div style="padding: 2rem;display:flex;justify-content: center">
            <?php $solutionForm->button("Submit",'submit','confirm') ?>
        </div>



    </div>

    <?php $solutionForm->end() ?>

</div>





