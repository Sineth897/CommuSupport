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

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Donors"); ?>

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
