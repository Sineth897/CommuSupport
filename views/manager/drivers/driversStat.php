<link rel="stylesheet" href="../../public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../../public/CSS/charts/charts.css">

<?php

/**
 * @var $dates array
 */

$manager = \app\models\managerModel::getModel(['employeeID' => $_SESSION['user']]);

$driverStats = \app\models\driverModel::getDriverDeliveryCountStatisticsUnderCCMonthBack($manager->ccID);

$deliveryByDate = \app\models\deliveryModel::getDeliveriesDoneUnderCCMonthBack($manager->ccID);

$deliveryByDistance = \app\models\deliveryModel::getDeliveriesDoneUnderCCMonthBackByDistance($manager->ccID);

//echo "<pre>";
//print_r($deliveryByDistance);
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

        $tableHeaders = ['Name', 'Vehicle','Preference','No of Deliveries','Total Distance' ];
        $arrayKeys = ['name','vehicleType','preference','deliveries','distance'];

        $driverStatTable = new \app\core\components\tables\table($tableHeaders, $arrayKeys);

        $driverStatTable->displayTable($driverStats);

    ?>

</div>

<script>

    window.onload = function () {
        document.getElementById("statPrint").onclick = function () {
            window.print();
        }
    }

    let deliveryData = <?php echo json_encode($deliveryByDate); ?>;

    Object.keys(deliveryData).forEach(key => {
        deliveryData[key.substring(5)] = deliveryData[key];
        delete deliveryData[key];
    });

    const dates = <?= json_encode($dates) ?>;

    const deliveryByDistance = <?= json_encode($deliveryByDistance) ?>;

</script>

<script type="module" src="../../public/JS/charts/manager/drivers/deliveryVariations.js"></script>
<script type="module" src="../../public/JS/charts/manager/drivers/deliveryByDistance.js"></script>

