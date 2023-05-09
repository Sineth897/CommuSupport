<link href="../public/CSS/table/table-styles.css" type="text/css" rel="stylesheet" >
<link href="../public/CSS/button/button-styles.css" type="text/css" rel="stylesheet" >

<?php
/** @var $model \app\models\choModel */

$userID = \app\core\Application::session()->get('user');
$userData = $model->viewUsers($userID);


?>




<?php

$profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end();

?>


<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Users");

$headerDiv->end();
?>



<?php

$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filterForm = \app\core\components\form\form::begin('', '');
$filterForm->dropDownList($model, "Select a Type", '',['donor'=>'Donor','donee'=>'Donee','organization'=>'Organization','individual'=>'Individual'], 'filterCategory');
$filterForm::end();

$searchDiv->filterEnd();




$searchDiv->filterDivEnd();


$searchDiv->end();
?>


<div class="search-filter">



    <div class="search" >
        <input type="text" placeholder="Search" name="find" id="search">
        <a href="#"><i class="material-icons">search</i></a>
    </div>

</div>





<div class="content">


  <?php


  $header = ["User Name","User Type","Email","Address","Contact Number","Type"];

  $arrayKey = ["userName","userType","email","address","contactNumber","type"];

  $donorTable = new \app\core\components\tables\table($header,$arrayKey);
  $donorTable->displayTable($userData);

    ?>



</div>


<script type="module" src="../public/JS/cho/users/filter.js"></script>



