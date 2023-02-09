<link rel="stylesheet" href="/CommuSupport/public/CSS/cards/eventcard.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">

<?php

/** @var $model \app\models\eventModel */

use app\core\Application;

$managerID = Application::$app->session->get('user');
$manager = new \app\models\managerModel();
$manager = $manager->findOne(['employeeID' => $managerID]);
$ccID = $manager->ccID;

$events = $model->retrieve(["ccID" => $ccID],["date" => "DESC"]);
?>

<!--profile div-->
<div class="profile">
    <div class="notif-box">
        <i class="material-icons">notifications</i>
    </div>
    <div class="profile-box">
        <div class="name-box">
            <h4>Username</h4>
            <p>Position</p>
        </div>
        <div class="profile-img">
            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile">
        </div>
    </div>
</div>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Events");

$headerDiv->pages(["ongoing", "completed", "cancelled"]);

$headerDiv->end();
?>


<!--        Search and filter boxes -->
<?php
$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filters();

$creatEvent = \app\core\components\form\form::begin('./events/create', 'get');

echo "<button class='btn-cta-primary'> Publish event </button>";

$creatEvent->end();

$searchDiv->end();
?>
<div class="content">
<?php
$eventCards = new \app\core\components\cards\eventcard();
$eventCards->displayEvents($events);

?>
</div>


<div>
    <?php $filter = \app\core\components\form\form::begin('', ''); ?>

    <?php $filter->dropDownList($model,"Event Type","eventCategory",$model->getEventCategories(),"eventCategory")?>

    <button type="button" id="filterBtn">Filter</button>

    <?php $filter->end(); ?>
</div>



<script type="module" src="/CommuSupport/public/JS/manager/events/view.js"></script>
