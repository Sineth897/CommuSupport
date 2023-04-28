<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/statistics/charts/charts.css">
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

$profile->notification();

$profile->profile();

$profile->end(); ?>

<!-- Inforgraphic Cards Layout -->
<?php $infoDiv = new \app\core\components\layout\infoDiv();

// First Block of Statistics
$infoDiv->statDivStart();
//
$infoDiv->chartCanvas("itemChart");
?>
<!-- Summary of ALl Statistics in this div-->
<?php
$infoDiv->statDivEnd(); ?>

<!--Second Long Div with Bar Chart-->
<?php $infoDiv->chartDivStart(); ?>
<div class="chart-container">
<?php $infoDiv->chartCanvas("totalChart"); ?>
</div>
<?php
$urgencies = array("Within 7 days", "Within a month");
$results = $model->getRequestDataMonthly();
?>

<script>
    const weekData = <?php echo json_encode($results[$urgencies[0]]); ?>;
    const monthData = <?php echo json_encode($results[$urgencies[1]]); ?>;
</script>
<script src="/CommuSupport/public/JS/charts/admin/request/totalChart.js"></script>

<?php $infoDiv->chartDivEnd();
$infoDiv->end(); ?>


<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Requests"); ?>

<?php $headerDiv->pages(["pending", 'accepted']) ?>

<?php $headerDiv->end(); ?>

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
<div class="content" id="pendingRequestsTable">
    <?php
    $requests = $model->getPendingRequestWithPostedBy();

    $header = ["PostedBy", "Approval", "Item", "Amount", "Posted Date"];
    $arrayKey = ["username", "approval", "subcategoryName", "amount", "postedDate", ['', 'View', '#', [], 'requestID']];

    $requestTable = new table($header, $arrayKey);

    $requestTable->displayTable($requests);
    ?>
</div>

<div class="content" id="acceptedRequestsTable">
    <?php
    $accepteRequests = $model->getAcceptedRequestWithPostedBy();

    $header = ["Posted By", "Accepted By", "Item", "Amount", "Delivery"];
    $arrayKey = ["username", "acceptedBy", "subcategoryName", "amount", "deliveryStatus", ['', 'View', '#', [], 'acceptedID']];

    $requestTable = new table($header, $arrayKey);

    $requestTable->displayTable($accepteRequests);
    ?>
</div>

<script type="module" src="../public/JS/admin/request/view.js"></script>



