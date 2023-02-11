<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

/** @var $model \app\models\donorModel */

$donors = $model->retrieve();

$headers = ["ID",'Registered Date','Email','Address',"Contact Number","Type"];
$arrayKeys = ["donorID",'registeredDate','email','address','contactNumber','type'];
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

<?php $headerDiv->heading("Donees"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>


<div id="doneeDisplay" class="content">

    <?php $individualTable = new \app\core\components\tables\table($headers,$arrayKeys); ?>

    <?php
    $individualTable->displayTable($donors);
    ?>

</div>



