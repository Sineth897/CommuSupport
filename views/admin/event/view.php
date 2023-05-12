<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">

<?php

use app\core\components\tables\table;

/** @var $model \app\models\eventModel */
/** @var $user \app\models\adminModel */

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>


<!-- Inforgraphic Cards Layout -->
<?php $infoDiv = new \app\core\components\layout\infoDiv([2, 3]);

// First Block of Statistics
$infoDiv->chartDivStart();
//?>
<div class="chart-container">
    <canvas id="itemChart"></canvas>
</div>
<?php
$infoDiv->chartDivEnd();

?>


<!--Second Long Div with Bar Chart-->
<?php $infoDiv->chartDivStart(); ?>
<div class="chart-container">
    <canvas id="totalChart" width="600"></canvas>
</div>
<?php
$categories = array_values($model->getEventCategories());

$chartData2 = $model->getEventPartbyMonth();


?>

<?php
foreach ($categories as $category) { ?>

    <script>
        const <?php echo str_replace(' ', '_', $category); ?> = <?php echo json_encode($chartData2[$category]); ?>;
    </script>
    <?php
    echo $category;
    print_r($chartData2[$category]);
    echo "<br>";
}
?>

<?php $infoDiv->chartDivEnd();
$infoDiv->end(); ?>



<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Events"); ?>

<?php $headerDiv->end(); ?>


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

$searchDiv->search();

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