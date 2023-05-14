<link rel="stylesheet" href="../../public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../../public/CSS/charts/charts.css">

<?php

/**
 * @var $dates array
 */

$logistic = \app\models\logisticModel::getModel(['employeeID' => $_SESSION['user']]);

$inventory = \app\models\inventorylog::getInventoryLogsOfCCMonthBack($logistic->ccID);

$inventoryChartDate = \app\models\inventorylog::getInventoryLogsOfMonthBackGroupByDate($logistic->ccID);

$currentInventory = \app\models\inventoryModel::getCurrentInventoryOfGivenCCByCategories($logistic->ccID);

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

        .heading-pages {
            justify-content: center;
        }

        .info-container .grid-1-2 .chart-container canvas{
            padding: 50px !important;
            width: 50% !important;
        }

        td {
            font-size: .5rem;
        }

    }

</style>

<script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>


<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>


<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Log of last 30 days"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();


echo "<div></div>";
echo "<button class='btn-cta-primary' id='logPrint'> Get PDF </button>";

$searchDiv->end(); ?>

<?php $infoDiv = new \app\core\components\layout\infoDiv([1,2]); ?>

<?php $infoDiv->chartDivStart(); ?>

<div class="chart-test chart-container">

    <p> Current inventory by category </p>
    <canvas id="currentInventory" height="180%"  ></canvas>

</div>

<?php $infoDiv->chartDivEnd(); ?>

<?php $infoDiv->chartDivStart(); ?>

<div class="chart-test chart-container">

    <p> Variation of logs entries over last month </p>
    <canvas id="inventoryVariation" height="75%"></canvas>

</div>

<?php $infoDiv->chartDivEnd(); ?>


<?php $infoDiv->end(); ?>

<div class="content">

    <?php

    $tableHeaders = ['Item', 'Amount', 'Remark', 'Date'];
    $arrayKeys = ['subcategoryName', 'amount', 'remark', 'dateReceived'];

    $inventoryLogTable = new \app\core\components\tables\table($tableHeaders, $arrayKeys);

    $inventoryLogTable->displayTable($inventory);

    ?>

</div>

<script>

    window.onload = function () {
        document.getElementById("logPrint").onclick = function () {
            window.print();
        }
    }

    let inventoryData = <?php echo json_encode($inventoryChartDate); ?>;

    Object.keys(inventoryData).forEach(key => {
        inventoryData[key.substring(5)] = inventoryData[key];
        delete inventoryData[key];
    });

    const dates = <?= json_encode($dates) ?>;

    const currentInventory = <?= json_encode($currentInventory) ?>;

</script>

<script type="module" src="../../public/JS/charts/logistic/inventory/inventoryVariations.js"></script>
<script type="module" src="../../public/JS/charts/logistic/inventory/currentInventory.js"></script>
