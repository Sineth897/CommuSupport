<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">


<?php
/** @var $solution \app\models\complaintModel */
/** @var $user \app\models\choModel */

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

    <?php $solutionForm = \app\core\components\form\form::begin('','post') ?>

    <div class="form-box">


        <div >

            <?php $solutionForm ->textArea($solution,"Please provide the solution" ,"solution") ?>

        </div>

        <div style="display:none;">

            <?php $solutionForm->inputField($solution,'ComplaintID','text','complaintID') ?>
        </div>


        <div style="padding: 2rem;display:flex;justify-content: center">
            <?php $solutionForm->button("Submit",'submit','confirm') ?>
        </div>



    </div>

    <?php $solutionForm->end() ?>

</div>





