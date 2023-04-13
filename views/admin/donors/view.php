<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

/** @var $model \app\models\donorModel */

$CCs = \app\models\ccModel::getCCs();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Donors"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model,"Community center","cc",$CCs,"ccFilter");
$filter->dropDownList($model,"Type","type",['Individual' => 'Individual','Organization' => 'Organization'],"typeFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model,"Registered Date","registeredDate","registeredDateSort");
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>


<div id="donorTable" class="content">

    <?php
    $donors = $model->retrieveWithJoin('users','userID',[],[],'donorID');

    //adding relevant ceommunity center for each donee
    foreach ($donors as $key => $donor) {
        $donors[$key]['cc'] = $CCs[$donor['ccID']];
    }

    $headers = ["Username",'Registered Date','Community Center',"Contact Number","Type"];
    $arrayKeys = ["username",'registeredDate','cc','contactNumber','type',['','View','#',[],'donorID']];

    $individualTable = new \app\core\components\tables\table($headers,$arrayKeys);


    $individualTable->displayTable($donors);
    ?>

</div>

<script type="module" src="../public/JS/admin/donor/view.js"></script>