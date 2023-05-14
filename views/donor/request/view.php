<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/cards/request-card.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $model \app\models\requestModel */
/** @var $user \app\models\donorModel */

use app\models\acceptedModel;

$requests = $model->getAllRequests(['Approved']);
//$user = $user->findOne(['donorID' => $_SESSION['user']]);
$acceptedRequests = acceptedModel::getAcceptedRequestsByUserID($_SESSION['user']);

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Posted Requests");

$headerDiv->pages(["posted", "accepted"]);

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

<div class="content"  >

    <div class="card-container" id="postedRequests">
    <?php
    $requestCards = new \app\core\components\cards\requestcard();

    $requestCards->displayRequests($requests,[["View","requestView"]]);
    ?>

    </div>

    <div class="card-container" id="acceptedRequests" style="display: none">

        <?php

        $requestCards->displayRequests($acceptedRequests,[["View","requestView"]],true);
        ?>

    </div>

</div>


<script type="module" src="../public/JS/donor/request/view.js"></script>
