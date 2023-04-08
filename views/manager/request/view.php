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

//$history = array_filter($requests,function($request) {
//    return $request->status === 'completed';
//});

?>


<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>


<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Requests");

$headerDiv->pages(["pending","posted","history"]);

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

<div class="content" id="completedRequests" style="display: none">

    <?php
    echo '<pre>';
    print_r($pending);
    echo '</pre>';
    ?>


</div>

<script type="module" src="../public/JS/manager/requests/view.js"></script>