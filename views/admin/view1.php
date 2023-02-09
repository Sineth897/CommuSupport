<?php $regForm = \app\core\components\form\form::begin('logout', 'post'); ?>

<!-- <button> logout </button> -->


<div>
    <?php $regForm->dropDownList($model, 'category', 'text', 'category'); ?>
</div>

<div>
    <?php $regForm->dropDownList($model, 'subcategory', 'text', 'subcategory'); ?>
</div>



<?php $regForm->end(); ?>
