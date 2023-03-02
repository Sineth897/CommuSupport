<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<?php

/** @var $delivery \app\models\deliveryModel */

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Create a deliveries"); ?>

<?php $headerDiv->end(); ?>

<?php $deliveryform = \app\core\components\form\form::begin('','post'); ?>


<div class="form-group">
    <label class="form-label">Select processes :</label>
    <select class="basic-input-field">
        <option>Select</option>
    </select>
</div>

<div class="form-group">
    <label class="form-label">Select City :</label>
    <select class="basic-input-field">
        <option>Select</option>
    </select>
</div>

<div class="form-group">
    <label class="form-label">Driver (optional) :</label>
    <select class="basic-input-field">
        <option>Select</option>
    </select>
</div>


<div>
<?php $deliveryform->button('Confirm');?>

<?php $deliveryform::end();?>