<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<?php

use app\core\components\tables\table;

/** @var $model \app\models\driverModel */
/** @var $user \app\models\adminModel */

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
            background-color: var(--background-main) !important;
            -webkit-print-color-adjust: exact;
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

<!--        Profile Details-->

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Drivers"); ?>

<?php $headerDiv->end(); ?>


<?php $infoDiv = new \app\core\components\layout\infoDiv([1, 2, 1]); ?>


<?php $infoDiv->chartDivStart();
// retrieving the data for driver by vehicle type chart
$chartData = $model->getDriverbyVehicle();
?>
<script>
    const chartData = <?php echo json_encode($chartData); ?>;
</script>
<div class="stat-container">
    <p>Driver Vehicles</p>
    <canvas id="vehicleTypeChart" width="500">
    </canvas>
</div>

<script src="../public/JS/charts/admin/driver/driverVehicleChart.js"></script>
<?php $infoDiv->chartDivEnd(); ?>

<?php $infoDiv->chartDivStart(); ?>
<div class="stat-container">
    <p>Deliveries throughout the Years</p>
    <canvas id="totalDeliveryChart" width="100px">
    </canvas>
    <?php
    $chartData2 = $model->getDeliveryMonthly();
    //    $model->getDeliveryMonthly();
//    print_r($chartData2);
    ?>
    <script>
        const moreThan10 = <?php echo json_encode($chartData2['>10km']); ?>;
        const lessThan10 = <?php echo json_encode($chartData2['<10km']); ?>;
    </script>
<script src="../public/JS/charts/admin/driver/deliveryTotalChart.js"></script>

</div>
<?php $infoDiv->chartDivEnd(); ?>
<?php
$statData = $model->getDriverStats();
?>
<div class="stat-box-2-h">
    <div class="stat-card">
        <span class="stat-title">
           Ongoing Deliveries
        </span>
        <span class="stat-value">
            <?php
            echo $statData['Ongoing'];
            ?>
        </span>
        <span class="stat-movement">
            <i class="material-icons">local_shipping</i>
        </span>

    </div>
    <div class="stat-card">
                <span class="stat-title">
           Completed Deliveries
        </span>
        <span class="stat-value">
            <?php
            echo $statData['Completed'];
            ?></span>
        <span class="stat-movement ">
            <i class="material-icons">inventory_2</i>
        </span>

    </div>
</div>

<?php $infoDiv->End(); ?>


<!-- Search, Sort, Filter Divs -->
<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model, "Community center", "cc", $CCs, "ccFilter");
$filter->dropDownList($model, "Vehicle Type", "vehicleType", $model->getVehicleTypes(), "vehicleTypeFilter");
$filter->dropDownList($model, "Preference", "preference", $model->getPreferences(), "preferenceFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model, "Age", "age", "ageSort");
$sort->end();


$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

echo "<div class='btn-together' >";

$searchDiv->search();

echo "<button class='btn-primary' id='driverPrint'>Get PDF</button>";

echo "<a class='btn-primary' href='./drivers/stat'>View Driver Statistics</a>";

echo "</div>";

$searchDiv->end(); ?>

<!--        Content Block-->
<div class="content" id="driverTable">
    <?php
    $drivers = $model->retrieve();

    foreach ($drivers as $key => $driver) {
        $drivers[$key]['cc'] = $CCs[$driver['ccID']];
    }

    $header = ["Name", "Age", "ContactNumber", 'Vehicle', "Vehicle No", "Community Center"];

    $arrayKey = ["name", "age", "contactNumber", 'vehicleType', 'vehicleNo', "cc", ['', 'View', './drivers/individual/view', ['employeeID'], 'employeeID']];

    $driverTable = new table($header, $arrayKey);

    $driverTable->displayTable($drivers);

    ?>
</div>

<script type="module" src="../public/JS/admin/driver/view.js"></script>

<script>

    window.onload = function () {
        document.getElementById('driverPrint').addEventListener('click', function () {
            window.print();
        })
    }

</script>


