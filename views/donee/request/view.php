<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/cards/request-card.css">
<?php
/** @var $model \app\models\requestModel */
/** @var $user  \app\models\doneeModel*/

use app\core\Application;

$user = $user->findOne(['doneeID' => Application::$app->session->get('user')]);
$requests = $model->getOwnRequests($_SESSION['user']);

?>


<!--profile div-->
<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Your Requests");

$headerDiv->pages(["active", "completed"]);

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

$creatEvent = \app\core\components\form\form::begin('./request/create', 'get');

echo "<button class='btn-cta-primary'> Request </button>";

$creatEvent->end();

$searchDiv->end();
?>


<div class="content card-container" id="activeRequests">

    <?php
    $requestCards = new \app\core\components\cards\requestcard();

    $requestCards->displayRequests($requests,[['View','viewRequest']]);
    ?>

</div>

<div class="content card-container" id="completedRequests">

    <?php
    echo "<pre>";
    echo "Completed Requests";
    echo "</pre>";
    ?>

</div>

<script type="module" src="../public/JS/donee/request/view.js"></script>
