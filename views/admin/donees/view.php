<link rel="stylesheet" href="../public/CSS/table/table-styles.css">

<?php

/** @var $model \app\models\doneeModel */

$CCs = \app\models\ccModel::getCCs();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<!-- Inforgraphic Cards Layout -->
<?php $infoDiv = new \app\core\components\layout\infoDiv([2,3]);

// First Block of Statistics
$infoDiv->chartDivStart();
//?>
<div class="chart-container">
<canvas id="doneeCategoryChart"></canvas>
</div>

<?php
$chartData1 = $model->getDoneebyCategory();
?>
<script>
    const doneeData = <?php echo json_encode($chartData1); ?>;
</script>
<script src="../public/JS/charts/admin/donee/doneeCategoryChart.js"></script>

<!-- Summary of ALl Statistics in this div-->
<?php
$infoDiv->chartDivEnd();

?>


<!--Second Long Div with Bar Chart-->
<?php $infoDiv->chartDivStart(); ?>
<div class="chart-container">
<!--    --><?php //$infoDiv->chartCanvas("totalRegChart"); ?>
    <canvas id="totalRegChart" width="400" height="250"></canvas>
</div>

<?php
$chartData2 = $model->getDoneeRegMonthly();
?>

<script>
    const monthData = <?php echo json_encode($chartData2); ?>;
</script>
<script src="../public/JS/charts/admin/donee/totalRegChart.js"></script>

<?php $infoDiv->chartDivEnd();
$infoDiv->end(); ?>


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
