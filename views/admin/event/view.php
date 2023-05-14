<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">

<?php

use app\core\components\tables\table;

/** @var $model \app\models\eventModel */
/** @var $user \app\models\adminModel */

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>





<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Events"); ?>

<?php $headerDiv->end(); ?>

<!-- Inforgraphic Cards Layout -->
<?php $infoDiv = new \app\core\components\layout\infoDiv([1,2,1]);

// First Block of Statistics
$infoDiv->chartDivStart();
//?>
<div class="chart-container">
    <p>Event Categories</p>
    <canvas id="itemChart" height="280px"></canvas>
</div>
<?php
$chartData1 = $model->getEventbyCategory();

?>
<script>
    const itemData = <?php echo json_encode($chartData1)?>;

</script>
<script src="../public/JS/charts/admin/event/eventCategoryChart.js"></script>
<?php
$infoDiv->chartDivEnd();

?>


<!--Second Long Div with Bar Chart-->
<?php $infoDiv->chartDivStart(); ?>
<div class="chart-container">
    <p>Event Participation by Categories</p>
    <canvas id="totalChart" height="140px"></canvas>
</div>
<?php
$categories = array_values($model->getEventCategories());

$chartData2 = $model->getEventPartbyMonth();
foreach ($categories as $category) { ?>

    <script>
        const <?php echo str_replace(' ', '_', $category); ?> = <?php echo json_encode($chartData2[$category]); ?>;
    </script>
<?php } ?>
<script src="../public/JS/charts/admin/event/totalChart.js"></script>
<?php
$infoDiv->chartDivEnd();
?>
<div class="stat-box-2-h">
    <div class="stat-card">
        <span class="stat-title">
           Finished Events
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
            Upcoming Events
        </span>
        <span class="stat-value">
            100

        </span>
        <span class="stat-movement inc">
            <i class="material-icons">arrow_upward</i>10%
        </span>

    </div>
</div>
<?php
$infoDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model, "Community center", "cc", \app\models\ccModel::getCCs(), "ccFilter");
$filter->dropDownList($model, "Event Category", "eventCategoryID", $model->getEventCategories(), "categoryFilter");
$filter->dropDownList($model, "Status", "", ['Upcoming' => 'Upcoming', 'Ongoing' => 'Ongoing', 'Completed' => 'Completed'], "statusFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model, "Date", "date", "dateSort");
$sort->checkBox($model, 'Participation Count', 'participationCount', 'participationCountSort');
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

echo "<div class='btn-together'>";

$searchDiv->search();

echo "<a class='btn-primary' href='./events/stats'>View event statistics</a>";

echo "</div>";

$searchDiv->end(); ?>

<!--        Content Block-->
<div class="content" id="eventTable">
    <?php
    $events = $model->retrieve();

    $header = ["Theme", "OrganizedBy", "Location", "Date", "Status",];

    $arrayKey = ["theme", "organizedBy", "location", "date", "status", ['', 'View', '#', [], 'eventID']];

    $eventTable = new table($header, $arrayKey);

    $eventTable->displayTable($events);

    ?>
</div>

<script type="module" src="../public/JS/admin/event/view.js"></script>