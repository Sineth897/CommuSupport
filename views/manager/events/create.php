<?php

/** @var $model \app\models\eventModel */

echo \app\core\Application::session()->getFlash('result');

?>

<?php $creatEventForm = \app\core\components\form\form::begin('./create', 'post'); ?>

<div>
    <?php $creatEventForm->dropDownList($model, 'Event category', 'eventCategory', $model->getEventCategories()); ?>
</div>

<div>
    <?php $creatEventForm->inputField($model, 'Event Theme','text','theme'); ?>
</div>

<div>
    <?php $creatEventForm->inputField($model, 'Event organized by','text','organizedBy'); ?>
</div>

<div>
    <?php $creatEventForm->inputField($model, 'Contact number','text','contact'); ?>
</div>

<div>
    <?php $creatEventForm->inputField($model, 'Event date','date','date'); ?>
</div>

<div>
    <?php $creatEventForm->inputField($model, 'Event time','time','time'); ?>
</div>

<div>
    <?php $creatEventForm->inputField($model, 'Event location','text','location'); ?>
</div>

<div>
    <?php $creatEventForm->textArea($model, 'Event description','description'); ?>
</div>

<?php $creatEventForm->button('Create');  ?>

<?php $creatEventForm->end(); ?>



