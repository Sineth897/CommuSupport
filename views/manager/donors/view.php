<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

/**
 * @var $model \app\models\donorModel
 * @var $user \app\models\managerModel
 */

use app\core\Application;
use app\core\components\tables\table;

$manager = \app\models\managerModel::getModel(['employeeID' => Application::session()->get('user')]);
$donors = $model->getAllDonors($manager->ccID);

$individualDonorHeaders = ['First Name','Last name','Contact Number','Email'];
$individualDonorKeys = ['fname','lname','contactNumber','email',['','View','#',[],'donorID']];

$organizationDonorHeaders = ['Organization Name','Representative Name','Contact Number','Email',];
$organizationDonorKeys = ['organizationName','representative','contactNumber','email',['','View','#',[],'donorID']];

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Donors"); ?>

<?php $headerDiv->pages(["individuals", "organizations"]); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

//$searchDiv->filterBegin();
//
//$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model,"Registered Date","registeredDate","registeredDateSort");
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>


<div id="individualDonorDisplay" class="content">

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

<div id="organizationDonorDisplay" class="content" style="display: none">

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

<script type="module" src="../public/JS/manager/donors/view.js"