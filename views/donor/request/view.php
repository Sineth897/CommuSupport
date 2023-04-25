<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/cards/request-card.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $model \app\models\requestModel */
/** @var $user \app\models\donorModel */

$requests = $model->getAllRequests(['Approved']);
//$user = $user->findOne(['donorID' => $_SESSION['user']]);
//$requests = $user->filterRequests($requests);

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Posted Requests");

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

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->end();
?>

<div class="card-container content">

    <?php
    $requestCards = new \app\core\components\cards\requestcard();

    $requestCards->displayRequests($requests,[["View","requestView"]]);
    ?>

</div>


<script type="module" src="../public/JS/donor/request/view.js"></script>
