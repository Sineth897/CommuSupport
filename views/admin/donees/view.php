<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

/** @var $model \app\models\doneeModel */

$CCs = \app\models\ccModel::getCCs();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Donees"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model,"Community center","cc",$CCs,"ccFilter");
$filter->dropDownList($model,"Verification Status","verificationStatus",[ "No" => "Not Verified", "Yes" => "Verified"],"verificationStatusFilter");
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


<div id="doneeTable" class="content">

    <?php
    $donees = $model->retrieveWithJoin('users','userID',[],[],'doneeID');
    //adding relevant ceommunity center for each donee
    foreach ($donees as $key => $donee) {
        $donees[$key]['cc'] = $CCs[$donee['ccID']];
    }

    $headers = ["Username",'Registered Date', 'Verified','Community Center',"Contact Number","Type"];
    $arrayKeys = ["username",'registeredDate',['verificationStatus','bool',['No','Yes']],'cc','contactNumber','type',['','View','#',[],'doneeID']];

    $individualTable = new \app\core\components\tables\table($headers,$arrayKeys);


    $individualTable->displayTable($donees); ?>

</div>


<script type="module" src="../public/JS/admin/donee/view.js"></script>
