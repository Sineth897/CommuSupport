<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

/** @var $model \app\models\donorModel */

$CCs = \app\models\ccModel::getCCs();

?>

<style>

    @media print {

        @page {
            size: landscape;
        }

        .sidenav, .profile, .search-filter {
            display: none;
        }

        .main {
            width: 100vw;
            left: 0;
            height: 100%;
            overflow: visible;
        }

        tbody td:last-child {
            display: none;
        }

    }


</style>

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
    <canvas id="donorCategoryChart"></canvas>
</div>

<?php
$chartData1 = $model->getDonorbyCategory();
?>
<script>
    const donorData = <?php echo json_encode($chartData1); ?>;
</script>
<script src="../public/JS/charts/admin/donor/donorCategoryChart.js"></script>

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
$chartData2 = $model->getDonorRegMonthly();
?>

<script>
    const monthData = <?php echo json_encode($chartData2); ?>;
</script>
<script src="../public/JS/charts/admin/donor/totalRegChart.js"></script>

<?php $infoDiv->chartDivEnd();
$infoDiv->end(); ?>



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

echo "<button class='btn-cta-primary' id='donorPrint'>Print</button>";

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

<script>

    window.onload = function () {
        document.getElementById("donorPrint").addEventListener("click", function() {
            window.print();
        })
    }

</script>