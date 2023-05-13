<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<?php

use app\core\components\tables\table;

/** @var $model \app\models\driverModel */
/** @var $user \app\models\adminModel */

$CCs = \app\models\ccModel::getCCs();

?>
<!--        Profile Details-->

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

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
    <p>Hello MF</p>
    <canvas id="vehicleTypeChart" width="500">
    </canvas>
</div>

<script src="../public/JS/charts/admin/driver/driverVehicleChart.js"></script>
<?php $infoDiv->chartDivEnd(); ?>

<?php $infoDiv->chartDivStart(); ?>
<div class="stat-container">

</div>
<?php $infoDiv->chartDivEnd(); ?>

<div class="stat-box-2-h">
    <div class="stat-card">
        <span class="stat-title">
           Short Distance
        </span>
        <span class="stat-value">
            100
        </span>
        <span class="stat-movement dec">
            <i class="material-icons">arrow_downward</i>10%
        </span>

    </div>
    <div class="stat-card">
                <span class="stat-title">
            Long Distance
        </span>
        <span class="stat-value">
            100

        </span>
        <span class="stat-movement inc">
            <i class="material-icons">arrow_upward</i>10%
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

$searchDiv->search();

$searchDiv->end(); ?>

<!--        Content Block-->
<div class="content" id="driverTable">
    <?php
    $drivers = $model->retrieve();

    foreach ($drivers as $key => $driver) {
        $drivers[$key]['cc'] = $CCs[$driver['ccID']];
    }

    $header = ["Name", "Age", "ContactNumber", 'Vehicle', "Vehicle No", "Community Center"];

    $arrayKey = ["name", "age", "contactNumber", 'vehicleType', 'vehicleNo', "cc", ['', 'View', '#', [], 'employeeID']];

    $driverTable = new table($header, $arrayKey);

    $driverTable->displayTable($drivers);

    ?>
</div>

<script type="module" src="../public/JS/admin/driver/view.js"></script>


