<?php

/**
 * @var $model \app\models\doneeModel
 * @var $user \app\models\managerModel
 */

use app\core\Application;
use app\core\components\tables\table;
use app\models\managerModel;

$manager = managerModel::getModel(['employeeID' => Application::session()->get('user')]);
$donees = $model->getAllDonees($manager->ccID);

$individualDoneeHeaders = ["First Name","Last Name","Age","Contact Number","Email","Address"];
$individualDoneeArrayKeys = ["fname","lname","age","contactNumber","email","address"];

$organizationDoneeHeaders = ["Organization Name","Representative Name","Contact Number","Email","Address"];
$organizationDoneeArrayKeys = ["organizationName","representative","contactNumber","email","address"];


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

<?php $headerDiv->pages(["individuals", "organizations"]); ?>

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


<div id="individualDoneeDisplay">

    <?php $individualTable = new table($individualDoneeHeaders,$individualDoneeArrayKeys); ?>

    <?php

    if($donees['individuals']) {
        $individualTable->displayTable($donees['individuals']);
    } else {
        echo "No Individual Donees";
    } ?>

</div>

<div id="organizationDoneeDisplay">

    <?php $organizationTable = new table($organizationDoneeHeaders,$organizationDoneeArrayKeys); ?>

    <?php

    if($donees['organizations']) {
        $organizationTable->displayTable($donees['organizations']);
    } else {
        echo "No Organizations";
    } ?>

</div>
