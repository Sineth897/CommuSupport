<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<?php

/** @var $user \app\models\userModel */
/** @var $donee \app\models\doneeModel */
/** @var $doneeIndividual \app\models\doneeIndividualModel */
/** @var $doneeOrganization \app\models\doneeOrganizationModel */

$CHOs = \app\models\choModel::getCHOs();

?>

<?php $doneeForm = \app\core\components\form\form::begin('','post') ?>

<?php $doneeForm->formHeader('Donee Sign Up');

$indOrOrg = new \app\core\components\layout\headerDiv();

$indOrOrg->pages(['individuals', 'organizations']);

$indOrOrg->end(); ?>

    <div style="display: none">
        <?php $doneeForm->inputField($donee, 'Donor Type','text','type','donorType'); ?>
    </div>

<?php $doneeForm->dropDownList($donee,'Choose District','',$CHOs,'district'); ?>

<?php foreach ($CHOs as $key => $value) : ?>
    <div id="<?php echo $key ?>" class="form-group" style="display: none">
        <?php $doneeForm->dropDownList($donee,'Choose City','ccID',\app\models\choModel::getCCsUnderCHO($key)); ?>
    </div>
<?php endforeach; ?>


<div id="individualForm" style="display: none">
    <?php $doneeForm->inputField($doneeIndividual, 'First Name','text','fname'); ?>

    <?php $doneeForm->inputField($doneeIndividual, 'Last Name','text','lname'); ?>

    <?php $doneeForm->inputField($doneeIndividual, 'Age','number','age'); ?>

    <?php $doneeForm->inputField($doneeIndividual, 'NIC','text','nic'); ?>
</div>

<div id="organizationForm" style="display: none">
    <?php $doneeForm->inputField($doneeOrganization, 'Organization Name','text','organizationName'); ?>

    <?php $doneeForm->inputField($doneeOrganization, 'Registration Number','text','regNo'); ?>

    <?php $doneeForm->inputField($doneeOrganization, 'Representative Name','text','representative'); ?>

    <?php $doneeForm->inputField($doneeOrganization, 'Representative Contact','text','representativeContact'); ?>

</div>

<?php $doneeForm->inputField($donee, 'Email','email','email'); ?>

<?php $doneeForm->inputField($donee, 'Address','text','address'); ?>

<?php $doneeForm->inputField($donee, 'Contact Number','text','contactNumber'); ?>

<?php $doneeForm->inputField($user, 'Username','text','username'); ?>

<?php $doneeForm->inputField($user, 'Password','password','password'); ?>

<?php $doneeForm->button('Register'); ?>

<?php $doneeForm->end() ?>


<script type="module" src="../public/JS/guest/register/donee.js"></script>
