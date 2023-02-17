<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/registration/reg-base.css">
<?php

/** @var $user \app\models\userModel */
/** @var $donee \app\models\doneeModel */
/** @var $doneeIndividual \app\models\doneeIndividualModel */
/** @var $doneeOrganization \app\models\doneeOrganizationModel */

$CHOs = \app\models\choModel::getCHOs();

?>

<div class="background">

    <div class="reg-form-container">

    <?php $doneeForm = \app\core\components\form\form::begin('','post') ?>

    <?php
    $indOrOrg = new \app\core\components\layout\headerDiv();

    $indOrOrg->heading('Donee Sign Up');

    $indOrOrg->pages(['individuals', 'organizations']);

    $indOrOrg->end(); ?>

        <div class="login-grid-2">

         <div>

             <div style="display: none">
                 <?php $doneeForm->inputField($donee, 'Donor Type','text','type','doneeType'); ?>
             </div>

             <?php $doneeForm->dropDownList($donee,'Choose District','district',$CHOs,'district'); ?>

             <?php foreach ($CHOs as $key => $value) : ?>
                 <div id="<?php echo $key ?>" class="form-group" style="display: none">
                     <?php $doneeForm->dropDownList($donee,'Choose City','ccID',\app\models\choModel::getCCsUnderCHO($key)); ?>
                 </div>
             <?php endforeach; ?>

             <?php $doneeForm->inputField($donee, 'Email','email','email'); ?>

             <?php $doneeForm->inputField($donee, 'Address','text','address'); ?>

             <?php $doneeForm->inputField($donee, 'Contact Number','text','contactNumber'); ?>

             <?php $doneeForm->inputField($user, 'Username','text','username'); ?>

             <?php $doneeForm->inputField($user, 'Password','password','password'); ?>

             <?php $doneeForm->inputField($user, 'Confirm Password','password','confirmPassword'); ?>

         </div>

        <div>

            <div id="individualForm" >
                <?php $doneeForm->inputField($doneeIndividual, 'First Name','text','fname'); ?>

                <?php $doneeForm->inputField($doneeIndividual, 'Last Name','text','lname'); ?>

                <?php $doneeForm->inputField($doneeIndividual, 'Age','number','age'); ?>

                <?php $doneeForm->inputField($doneeIndividual, 'NIC','text','nic'); ?>

                <?php $doneeForm->fileInput($donee,'Upload your NIC Front','nicFront'); ?>

                <?php $doneeForm->fileInput($donee,'Upload your NIC Back','nicBack'); ?>
            </div>

            <div id="organizationForm" style="display: none">
                <?php $doneeForm->inputField($doneeOrganization, 'Organization Name','text','organizationName'); ?>

                <?php $doneeForm->inputField($doneeOrganization, 'Registration Number','text','regNo'); ?>

                <?php $doneeForm->fileInput($donee,'Upload your registration certificate front','certificateFront'); ?>

                <?php $doneeForm->fileInput($donee,'Upload your NIC certificate back','certificateBack'); ?>

                <?php $doneeForm->inputField($doneeOrganization, 'Representative Name','text','representative'); ?>

                <?php $doneeForm->inputField($doneeOrganization, 'Representative Contact','text','representativeContact'); ?>

                <?php $doneeForm->inputField($doneeOrganization, 'How many dependents are present? (If only applicable)','number','capacity'); ?>

            </div>

        </div>

        </div>

<?php $doneeForm->button('Register'); ?>

<?php $doneeForm->end() ?>

    </div>

</div>


<script type="module" src="../public/JS/guest/register/donee.js"></script>

<script>
    document.getElementById('doneeType').value = 'Individual';
</script>

<?php if(isset($_POST['type'])) : ?>
    <script>
        <?php if($_POST['type'] == 'Individual') : ?>
        document.getElementById('doneeType').value = 'Individual';
        document.getElementById('organizationForm').style.display = 'none';
        document.getElementById('individualForm').style.display = 'block';
        <?php else : ?>
        document.getElementById('doneeType').value = 'Organization';
        document.getElementById('individualForm').style.display = 'none';
        document.getElementById('organizationForm').style.display = 'block';
        <?php endif; ?>
    </script>
<?php endif?>

<?php if(isset($_POST['district'])) : ?>
    <script>
        document.getElementById('district').value = '<?php echo $_POST['district'] ?>';
        document.getElementById('<?php echo $_POST['district'] ?>').style.display = 'block';
    </script>
<?php endif?>
