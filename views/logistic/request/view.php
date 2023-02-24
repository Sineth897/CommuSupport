<?php


?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Requests");

$headerDiv->pages(["posted","history"]);

$headerDiv->end();
?>

<!--        Search and filter boxes -->
<?php
$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->end();
?>

<div class="content" id="postedRequests">
    <h3>Posted Requests</h3>
</div>

<div class="content" id="historyRequests">
    <h3>History</h3>
</div>

<script type="module" src="../public/JS/logistic/request/view.js"></script>

