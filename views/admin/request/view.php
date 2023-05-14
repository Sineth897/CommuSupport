<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

<?php

use app\core\components\tables\table;

/** @var $model \app\models\requestModel */
/** @var $user \app\models\adminModel */

?>
<!--        Profile Details-->

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>


<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Pending Requests"); ?>

<?php $headerDiv->pages(["pending", 'accepted']) ?>

<?php $headerDiv->end(); ?>



<!-- Inforgraphic Cards Layout -->
<?php $infoDiv = new \app\core\components\layout\infoDiv([1,2,1]);

// First Block of Statistics
$infoDiv->chartDivStart();
//?>
<div class="chart-container">
    <p>Category</p>
    <canvas id="itemChart" height="240px"></canvas>
</div>
<?php
$chartData1 = $model->getRequestDatabyCategory();
?>

<script>
    const itemData = <?php echo json_encode($chartData1)?>;
    console.log(itemData);
</script>
<script src="../public/JS/charts/admin/request/subcategoryChart.js"></script>
<!-- Summary of ALl Statistics in this div-->
<?php
$infoDiv->chartDivEnd();

?>



<!--Second Long Div with Bar Chart-->
<?php $infoDiv->chartDivStart(); ?>
<div class="chart-container">
    <p>Request Data </p>
    <canvas id="totalChart" height="120px"></canvas>
</div>
<?php
$urgencies = array("Within 7 days", "Within a month");
$chartData2 = $model->getRequestDataMonthly();
?>

<script>
    const weekData = <?php echo json_encode($chartData2[$urgencies[0]]); ?>;
    const monthData = <?php echo json_encode($chartData2[$urgencies[1]]); ?>;
</script>
<script src="../public/JS/charts/admin/request/totalChart.js"></script>

<?php $infoDiv->chartDivEnd();
?>

<div class="stat-box-2-h">
    <div class="stat-card">
        <span class="stat-title">
Pending Requests        </span>
        <span class="stat-value">
            80
        </span>
        <span class="stat-movement">
            <i class="material-icons">assignment_late</i>
        </span>

    </div>
    <div class="stat-card">
                <span class="stat-title">
Completed Requests        </span>
        <span class="stat-value">
            150

        </span>
        <span class="stat-movement">
            <i class="material-icons">assignment_turned_in</i>
        </span>

    </div>
</div>

<?php
$infoDiv->end(); ?>



<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model, "Subcategory", "item", \app\models\requestModel::getAllSubcategories(), "subcategoryFilter");
$filter->dropDownList($model, "Approval", "", ["Pending" => "Pending", "Approved" => "Approved"], "approvalFilter");
//$filter->dropDownList($model,"Type","type",['Individual' => 'Individual','Organization' => 'Organization'],"typeFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model, "Posted Date", "postedDate", "postedDateSort");
$sort->checkBox($model, "Amount", "amount", "amountSort");
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>

<!--        Content Block-->

<!--<img src="/CommuSupport/public/src/errors/404.svg">-->
<div class="content with-chart" id="pendingRequestsTable">
    <?php
    $requests = $model->getPendingRequestWithPostedBy();

    $header = ["PostedBy", "Approval", "Item", "Amount", "Posted Date"];
    $arrayKey = ["username", "approval", "subcategoryName", "amount", "postedDate", ['', 'View', '#', [], 'requestID']];

    $requestTable = new table($header, $arrayKey);

    $requestTable->displayTable($requests);
    ?>
</div>

<div class="content with-chart" id="acceptedRequestsTable" style="display: none">
    <?php
    $accepteRequests = $model->getAcceptedRequestWithPostedBy();

    $header = ["Posted By", "Accepted By", "Item", "Amount", "Delivery"];
    $arrayKey = ["username", "acceptedBy", "subcategoryName", "amount", "deliveryStatus", ['', 'View', '#', [], 'acceptedID']];

    $requestTable = new table($header, $arrayKey);

    $requestTable->displayTable($accepteRequests);
    ?>
</div>

<script type="module" src="../public/JS/admin/request/view.js"></script>



