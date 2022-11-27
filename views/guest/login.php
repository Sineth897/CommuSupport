<?php
/**   @var $model app\models\loginForm;*/
?>

<?php $regForm = \app\core\components\form\form::begin('', 'post'); ?>

    <div>
        <?php $regForm->inputField($model, 'Email', 'email', 'email'); ?>
    </div>

    <div>
        <?php $regForm->inputField($model, 'Password', 'password', 'password'); ?>
    </div>

    <div>
        <button type="submit">submit</button>
    </div>

<?php $regForm->end(); ?>