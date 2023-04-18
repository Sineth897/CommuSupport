<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="./public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="./public/CSS/flashMessages.css">

<?php
/** @var $donor \app\models\donorModel */
/** @var $complaint \app\models\complaintModel */
/** @var $cho \app\models\choModel */
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

            <?php $complaintRegistrationForm->inputField($complaint,'Filed By','text','filedBy') ?>

            <?php $complaintRegistrationForm->inputField($complaint,'Filed Date','text','filedDate') ?>

            <?php $complaintRegistrationForm->inputField($complaint,'Subject','text','subject') ?>

            <?php $complaintRegistrationForm->inputField($complaint,'Status','text','status') ?>

            <?php $complaintRegistrationForm->inputField($complaint,'Solution','text','solution') ?>


            <div style="display:none">
                <?php $complaintRegistrationForm->inputField($complaint,"Cho ID ",'hidden','choID')?>
            </div>

        </div>

        <div style="padding: 2rem;display:flex;justify-content: center">
            <?php $complaintRegistrationForm->button("File",'submit','confirm') ?>
        </div>

        <div class="form-split">
            <?php $complaintRegistrationForm->button("Confirm",'submit','confirm') ?>
        </div>

    </div>

    <?php $complaintRegistrationForm->end() ?>

</div>
