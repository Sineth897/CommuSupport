<?php

/** @var $model \app\models\requestModel */
/** @var $user \app\models\managerModel */

$user = $user->findOne(['employeeID' => \app\core\Application::session()->get('user')]);

$requests = $model->getRequestsUnderCC($user->ccID);

$pending = array_filter($requests,function($request) {
    return $request['approval'] === 0;
});

$published = array_filter($requests,function($request) {
    return $request['approval'] === 1;
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

$headerDiv->pages(["pending","published","history"]);

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

<div class="content" id="pendingRequests">

    <?php
    echo '<pre>';
    print_r($pending);
    echo '</pre>';
    ?>


</div>

<div class="content" id="postedRequests">

    <?php
    echo '<pre>';
    print_r($pending);
    echo '</pre>';
    ?>


</div>

<div class="content" id="completedRequests">

    <?php
    echo '<pre>';
    print_r($pending);
    echo '</pre>';
    ?>


</div>