<link rel="stylesheet" href="../../public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../../public/CSS/charts/charts.css">

<?php

/**
 * @var $dates array
 */

$eventDetails = \app\models\eventModel::getEventDetailsMonthBack();

$eventByType = \app\models\eventModel::getEventDetailsWithTypeMonthBack();

$eventsFinished = \app\models\eventModel::getEventFinishedMonthBack();

echo "<pre>";
print_r($eventsFinished);
echo "</pre>";
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

<?php $headerDiv->heading("Events published of Last 30 Days"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();


echo "<div></div>";
echo "<button class='btn-cta-primary' id='statPrint'> Get PDF </button>";

$searchDiv->end(); ?>

<?php $infoDiv = new \app\core\components\layout\infoDiv([1,2]); ?>

<?php $infoDiv->chartDivStart(); ?>

<div class="chart-test chart-container">

    <p> Types of events </p>
    <canvas id="chartByEventType" height="180%"  ></canvas>

</div>

<?php $infoDiv->chartDivEnd(); ?>

<?php $infoDiv->chartDivStart(); ?>

<div class="chart-test chart-container">

    <p> Events posted within last 30 days </p>
    <canvas id="eventPosted" height="75%"></canvas>

</div>

<?php $infoDiv->chartDivEnd(); ?>


<?php $infoDiv->end(); ?>

<div class="content">

    <?php

    $tableHeaders = ['Theme','Type',"Participated users",'Location','Community Center'];
    $arrKeys = ['theme','name','participationCount','location','city'];

    $table = new \app\core\components\tables\table($tableHeaders,$arrKeys);

    $table->displayTable($eventDetails);

    ?>


</div>

<script>

    window.onload = function () {
        document.getElementById("statPrint").onclick = function () {
            window.print();
        }
    }

    let eventData = <?php echo json_encode($eventsFinished); ?>;

    Object.keys(eventData).forEach(key => {
       eventData[key.substring(5)] = eventData[key];
       delete eventData[key];
    });

    const dates = <?= json_encode($dates) ?>;

    const eventsByType = <?= json_encode($eventByType) ?>;

</script>

<!--<script type="module" src="../../public/JS/charts/admin/driver/deliveryVariations.js"></script>-->
<script type="module" src="../../public/JS/charts/admin/event/eventTypeLastMonth.js"></script>
