<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/cards/request-card.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $model \app\models\requestModel */
/** @var $accepted \app\models\acceptedModel */
/** @var $user \app\models\logisticModel */

// get the logistic model of the logged-in user
$user = $user->findOne(['employeeID' => $_SESSION['user']]);

// retrieve all requests approved by managers
$requests = $model->getAllRequests(['Approved']);

// retrieve all requests accepted by the logistic ( both ongoing and completed )
$acceptedRequests = \app\models\acceptedModel::getAcceptedRequestsByUserID($user->ccID);

// filter the accepted requests to get only completed requests
$completedRequests = array_filter($acceptedRequests,function ($request){
    return $request['deliveryStatus'] === 'Completed';
});

// filter the accepted requests to get only ongoing requests
$ongoingRequests = array_filter($acceptedRequests,function ($request){
    return $request['deliveryStatus'] !== 'Completed';
});

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Requests");

$headerDiv->pages(["posted","accepted","completed"]);

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

<div class="content" >

    <div class="card-container" id="postedRequests">

        <?php $requestCards = new \app\core\components\cards\requestcard();

        $requestCards->displayRequests($requests,[["View","requestView"]]); ?>

    </div>

    <div class="card-container" id="acceptedRequests" style="display: none">

        <?php

        $requestCards->displayRequests($ongoingRequests,[["View","requestView"]],true);

//        echo "<pre>";
//        print_r($ongoingRequests);
//        echo "</pre>";
        ?>

    </div>

    <div class="card-container" id="completedRequests" style="display: none">

        <?php

        $requestCards->displayRequests($completedRequests,[["View","requestView"]],true);
        ?>

    </div>
</div>

<script type="module" src="../public/JS/logistic/request/view.js"></script>

