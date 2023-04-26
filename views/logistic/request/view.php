<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/cards/request-card.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $model \app\models\requestModel */
/** @var $accepted \app\models\acceptedModel */
/** @var $user \app\models\logisticModel */

$user = $user->findOne(['employeeID' => $_SESSION['user']]);
$requests = $model->getAllRequests(['Approved']);
$acceptedRequests = $accepted->getAcceptedRequests($user->ccID);

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

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

$filterForm = \app\core\components\form\form::begin('', '');
$filterForm->dropDownList($model, "Select a Category", '', \app\models\requestModel::getAllSubcategories(), 'filterCategory');
$filterForm->dropDownList($model, "Select urgency", '', $model->getUrgency(), 'filterUrgency');
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

<div class="content card-container" id="postedRequests">
    <?php
    $requestCards = new \app\core\components\cards\requestcard();

    $requestCards->displayRequests($requests,[["View","requestView"]]);

    echo "<pre>";
    print_r($acceptedRequests);
    echo "</pre>";
    ?>
</div>

<div class="content" id="historyRequests">
    <h3>History</h3>
</div>

<script type="module" src="../public/JS/logistic/request/view.js"></script>

