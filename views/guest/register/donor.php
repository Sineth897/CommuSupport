<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/registration/reg-base.css">
<?php

/**
 * @var $user \app\models\userModel
 * @var $donor \app\models\donorModel
 * @var $donorIndividual \app\models\donorIndividualModel
 * @var $donorOrganization \app\models\donorOrganizationModel
 */

$CHOs = \app\models\choModel::getCHOs();

?>

<div class="background">

        <div class="reg-form-container">

<?php $donorForm = \app\core\components\form\form::begin('','post') ?>

    <?php $indOrOrg = new \app\core\components\layout\headerDiv();

    $indOrOrg->heading('Donor Sign Up');

    $indOrOrg->pages(['individuals', 'organizations']);

    $indOrOrg->end(); ?>

            <div class="login-grid-2">

                <div>

                    <div style="display: none">
                        <?php $donorForm->inputField($donor, 'Donor Type','text','type','donorType'); ?>
                    </div>

                    <?php $donorForm->dropDownList($donor,'Choose District','district',$CHOs,'district'); ?>

                    <?php foreach ($CHOs as $key => $value) : ?>
                        <div id="<?php echo $key ?>" class="form-group" style="display: none">
                            <?php $donorForm->dropDownList($donor,'Choose City','ccID',\app\models\choModel::getCCsUnderCHO($key)); ?>
                        </div>
                    <?php endforeach; ?>

                    <?php $donorForm->inputField($donor, 'Email','email','email'); ?>

                    <?php $donorForm->inputField($donor, 'Address','text','address'); ?>

                    <?php $donorForm->inputField($donor, 'Contact Number','text','contactNumber'); ?>

                    <?php $donorForm->inputField($user, 'Username','text','username'); ?>

                    <?php $donorForm->inputField($user, 'Password','password','password'); ?>

                    <?php $donorForm->inputField($user, 'Confirm Password','password','confirmPassword'); ?>

                </div>

                <div>
                    <div id="individualForm" >
                        <?php $donorForm->inputField($donorIndividual, 'First Name','text','fname'); ?>

                        <?php $donorForm->inputField($donorIndividual, 'Last Name','text','lname'); ?>

                        <?php $donorForm->inputField($donorIndividual, 'Age','number','age'); ?>

                        <?php $donorForm->inputField($donorIndividual, 'NIC','text','nic'); ?>
                    </div>

                    <div id="organizationForm" style="display: none">
                        <?php $donorForm->inputField($donorOrganization, 'Organization Name','text','organizationName'); ?>

                        <?php $donorForm->inputField($donorOrganization, 'Registration Number','text','regNo'); ?>

                        <?php $donorForm->inputField($donorOrganization, 'Representative Name','text','representative'); ?>

                        <?php $donorForm->inputField($donorOrganization, 'Representative Contact','text','representativeContact'); ?>

                    </div>

                </div>



            </div>







<?php $donorForm->button('Register'); ?>

<?php $donorForm->end() ?>

        </div>

</div>


<script type="module" src="../public/JS/guest/register/donor.js"></script>

<script>
    document.getElementById('donorType').value = 'Individual';
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
    </script>
<?php endif?>


