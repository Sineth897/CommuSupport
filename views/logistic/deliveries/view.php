<?php

/** @var $deliveries \app\models\deliveryModel */
/** @var $user \app\models\logisticModel */

echo "<pre>";
var_dump($deliveries->retrieve());
echo "</pre>";


?>

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

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Deliveries"); ?>

<?php $headerDiv->pages(["pending", "completed"]); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv(); ?>

<?php $searchDiv->filters(); ?>

<?php $searchDiv->search(); ?>

<?php $searchDiv->end(); ?>
