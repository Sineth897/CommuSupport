<?php

/** @var $model \app\models\eventModel */

echo \app\core\Application::session()->getFlash('result');

?>

<!--profile div-->

<div class="profile">
    <div class="notif-box">
        <i class="material-icons">notifications</i>
    </div>
    <div class="profile-box">
        <div class="name-box">
            <h4>Username</h4>
            <p>Position</p>
        </div>
        <div class="profile-img">
            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile">
        </div>
    </div>
</div>


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

<?php $creatEventForm->button('Create');  ?>

<?php $creatEventForm->end(); ?>



