<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/cards/request-card.css">
<link rel="stylesheet" href="../public/CSS/cards/postedRequestCard.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php
/** @var $model \app\models\requestModel */
/** @var $user  \app\models\doneeModel*/

use app\core\Application;

$user = $user->findOne(['doneeID' => Application::$app->session->get('user')]);
$acceptedRequests = $model->getOwnRequests($_SESSION['user']);

//echo "<pre>";
//print_r($acceptedRequests);
//echo "</pre>";


?>


<!--profile div-->
<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Active Requests");

$headerDiv->pages(["active",'accepted' ,"completed"]);

$headerDiv->end();
?>

<?php
$checkVerification = new \app\core\components\layout\verificationDiv();

if($checkVerification->notVerified()) {
    return;
}
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

$creatEvent = \app\core\components\form\form::begin('./request/create', 'get');

echo "<button class='btn-cta-primary'> Request </button>";

$creatEvent->end();

$searchDiv->end();
?>

<div class="content">

<div class="posted-rq-card-container" id="activeRequests">

    <?php

    \app\core\components\cards\postedRequestCard::displayCards($acceptedRequests['activeRequests']);

    ?>

</div>

<div class="card-container" id="acceptedRequests" style="display:none;">

    <?php
    $requestCards = new \app\core\components\cards\requestCard();

    $requestCards->displayAcceptedRequetsToDonee($acceptedRequests['acceptedRequests'],);
    ?>

</div>

<div class="card-container" id="completedRequests" style="display: none">

    <?php
    $requestCards->displayRequests($acceptedRequests['completedRequests'],[['View','viewActiveRequest']],true);
    ?>

</div>

</div>

<script type="module" src="../public/JS/donee/request/view.js"></script>
