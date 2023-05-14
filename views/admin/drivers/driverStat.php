<link rel="stylesheet" href="../../public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../../public/CSS/charts/charts.css">

<?php

/**
 * @var $dates array
 */

use app\core\components\tables\table;

$driverStats = \app\models\driverModel::getDriverDeliveryCountStatisticsMonthBack();

$deliveryVariations = \app\models\deliveryModel::getDeliveriesDoneMonthBack();

$deliveryByDistance = \app\models\deliveryModel::getDeliveriesDoneMonthBackByDistance();

//echo "<pre>";
//print_r($deliveryVariations);
//echo "</pre>";

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

        #deliveryVariations {
            height: 60% !important;
            width: 95% !important;
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

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Delivery Stats of Last 30 Days"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();


echo "<div></div>";
echo "<button class='btn-cta-primary' id='statPrint'> Get PDF </button>";

$searchDiv->end(); ?>

<?php $infoDiv = new \app\core\components\layout\infoDiv([1,2]); ?>

<?php $infoDiv->chartDivStart(); ?>

<div class="chart-test chart-container">

    <p> Deliveries by Distance </p>
    <canvas id="chartByDistance" height="180%"  ></canvas>

</div>

<?php $infoDiv->chartDivEnd(); ?>

<?php $infoDiv->chartDivStart(); ?>

<div class="chart-test chart-container">

    <p> Deliveries completed over last month </p>
    <canvas id="deliveryVariations" height="75%"></canvas>

</div>

<?php $infoDiv->chartDivEnd(); ?>


<?php $infoDiv->end(); ?>


<div class="content">

    <?php

    $tableHeaders = ['Name', 'Vehicle','Preference','No of Deliveries','Total Distance','Community Center'];
    $arrayKeys = ['name','vehicleType','preference','deliveries','distance','city'];

    $driverTable = new table($tableHeaders, $arrayKeys);

    $driverTable->displayTable($driverStats);

    ?>

</div>

<script>

    window.onload = function () {
        document.getElementById("statPrint").onclick = function () {
            window.print();
        }
    }

    let deliveryData = <?php echo json_encode($deliveryVariations); ?>;

    Object.keys(deliveryData).forEach(key => {
        deliveryData[key.substring(5)] = deliveryData[key];
        delete deliveryData[key];
    });

    const dates = <?= json_encode($dates) ?>;

    const deliveryByDistance = <?= json_encode($deliveryByDistance) ?>;

</script>

<script type="module" src="../../public/JS/charts/admin/driver/deliveryVariations.js"></script>
<script type="module" src="../../public/JS/charts/admin/driver/deliveryByDistance.js"></script>