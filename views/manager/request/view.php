<link rel="stylesheet" href="../public/CSS/cards/request-card.css">
<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">

<?php

/** @var $model \app\models\requestModel */
/** @var $user \app\models\managerModel */

$user = $user->findOne(['employeeID' => \app\core\Application::session()->get('user')]);

$requests = $model->getRequestsUnderCC($user->ccID);

$pending = array_filter($requests,function($request) {
    return $request['approval'] === 'Pending';
});

$posted = array_filter($requests,function($request) {
    return $request['approval'] === "Approved";
});

$history = \app\models\acceptedModel::getCompletedRequestsUnderCC($user->ccID);

?>


<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>


<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Pending Requests");

$headerDiv->pages(["pending","posted","history"]);

$headerDiv->end();
?>

<!--        Search and filter boxes -->
<?php
$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filterForm = \app\core\components\form\form::begin('', '');
$filterForm->dropDownList($model, "Select a Category", '', \app\models\requestModel::getAllSubcategories(), 'filterCategory');
$filterForm::end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sortForm = \app\core\components\form\form::begin('', '');
$sortForm->checkBox($model,"Date Posted","",'sortByDatePosted');
$sortForm->checkBox($model, "Amount", "amount", 'sortByAmount');
$sortForm::end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->end();
?>

<div class="content card-container" id="pendingRequests">

        <?php
        $requestCards = new \app\core\components\cards\requestcard();

        $requestCards->displayRequests($pending,[["View","pendingRequestView"]]);

        ?>

</div>

<div class="content card-container" id="postedRequests" style="display: none">

    <?php
    $requestCards->displayRequests($posted,[["View","postedRequestView"]]);
    ?>


</div>

<div class="content card-container" id="completedRequests" style="display: none">

    <?php
    $requestCards->displayRequests($history,[["View","completedRequestView"]],true);
    ?>


</div>

<script type="module" src="../public/JS/manager/requests/view.js"></script>