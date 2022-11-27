
<?php
    /** @var $model app\models\regModel; */
?>

<?php $regForm = \app\core\components\form\form::begin('', 'post'); ?>


    <div>
        <?php $regForm->inputField($model, 'Name', 'text', 'name'); ?>
    </div>

    <div>
        <?php $regForm->inputField($model, 'Email', 'email', 'email'); ?>
    </div>

    <div>
        <?php $regForm->inputField($model, 'Password', 'password', 'password'); ?>
    </div>

    <div>
        <?php $regForm->inputField($model, 'Confirm Password', 'password', 'confirmPassword'); ?>
    </div>

    <div>
        <button type="submit">submit</button>
    </div>

<?php $regForm->end(); ?>



<?php
    if( isset($model) ){
        var_dump($model);
    }