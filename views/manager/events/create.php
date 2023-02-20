<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">

<?php

/** @var $model \app\models\eventModel */

echo \app\core\Application::session()->getFlash('result');

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<div class="content-form">
    <div class="form-box">
    <h3>Publish Event</h3>

<?php $creatEventForm = \app\core\components\form\form::begin('./create', 'post'); ?>

    <?php $creatEventForm->dropDownList($model, 'Event category', 'eventCategoryID', $model->getEventCategories()); ?>

    <?php $creatEventForm->inputField($model, 'Event Theme','text','theme'); ?>

    <?php $creatEventForm->inputField($model, 'Event organized by','text','organizedBy'); ?>

    <?php $creatEventForm->inputField($model, 'Contact number','text','contact'); ?>

    <div class="form-split">
        <?php $creatEventForm->inputField($model, 'Event date','date','date'); ?>

        <?php $creatEventForm->inputField($model, 'Event time','time','time'); ?>
    </div>

    <?php $creatEventForm->inputField($model, 'Event location','text','location'); ?>

    <?php $creatEventForm->textArea($model, 'Event description','description'); ?>

    <?php $creatEventForm->button('Publish');  ?>

<?php $creatEventForm->end(); ?>

    </div>
</div>
