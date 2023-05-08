<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/cards/eventcard.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $model \app\models\eventModel */

$events = $model->getAllUpcominAndActiveEvents();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Events");

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

    $filter = \app\core\components\form\form::begin('', '');
    $filter->dropDownList($model,"Event Type","eventCategory",$model->getEventCategories(),"eventCategory");
    $filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sortForm = \app\core\components\form\form::begin('', '');
$sortForm->checkBox($model,"By date","date",'sortByDate');
$sortForm::end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$creatEvent = \app\core\components\form\form::begin('./donations/create', 'get');

$creatEvent->end();

$searchDiv->end();
?>

<div class="content">
    <?php
    $eventCards = new \app\core\components\cards\eventcard();
    $eventCards->displayEvents($events);
    ?>
</div>

<script type="module" src="../public/JS/donee/event/view.js"></script>