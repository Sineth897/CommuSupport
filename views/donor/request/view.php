<?php

/** @var $model \app\models\requestModel */
/** @var $user \app\models\donorModel */

$userID = \app\core\Application::session()->get('user');
$user = $user->findOne(['donorID' => $userID]);
$request = $model->retrieve();

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

$headerDiv->heading("Posted Requests");

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
