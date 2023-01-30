<?php

echo "Hello, " . $_SESSION['username'];

?>



<?php $regForm = \app\core\components\form\form::begin('logout', 'post'); ?>

    <button> logout </button>

<?php $regForm->end(); ?>