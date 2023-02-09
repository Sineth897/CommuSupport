<?php

/**
 * @var $model \app\models\donorModel
 * @var $user \app\models\managerModel
 */

use app\core\Application;
use app\core\components\tables\table;

$manager = \app\models\managerModel::getModel(['employeeID' => Application::session()->get('user')]);
$donors = $model->getAllDonors($manager->ccID);

$individualDonorHeaders = ['First Name','Last name','Age','Contact Number','Email','Address'];
$individualDonorKeys = ['fname','lname','age','contactNumber','email','address'];

$organizationDonorHeaders = ['Organization Name','Representative Name','Contact Number','Email','Address'];
$organizationDonorKeys = ['organizationName','representativeName','contactNumber','email','address'];

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

<?php $headerDiv->heading("Donors"); ?>

<?php $headerDiv->pages(["individuals", "organizations"]); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv(); ?>

<?php $searchDiv->filters(); ?>

<?php $searchDiv->search(); ?>

<?php $searchDiv->end(); ?>


<div id="individualDonorDisplay">

    <?php $individualDonorTable =  new table($individualDonorHeaders,$individualDonorKeys); ?>

    <?php
     if($donors['individuals']) {
         $individualDonorTable->displayTable($donors['individuals']);
     }
     else {
         echo "No Individual Donors";
     }
    ?>

</div>

<div id="organizationDonorDisplay">

        <?php $organizationDonorTable =  new table($organizationDonorHeaders,$organizationDonorKeys); ?>

        <?php
        if($donors['organizations']) {
            $organizationDonorTable->displayTable($donors['organizations']);
        }
        else {
            echo "No Organization Donors";
        }
        ?>
</div>
