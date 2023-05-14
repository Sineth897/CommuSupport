<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="../public/CSS/unreg-user/unreg-bar.css">
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

$individualDoneeHeaders = ["First Name","Last Name","Is Verified","Contact Number","Email",];
$individualDoneeArrayKeys = ["fname","lname",['verificationStatus','bool',['No','Yes']],"contactNumber","email",['','View','#',[],'doneeID']];

$organizationDoneeHeaders = ["Organization Name","Representative Name",'Is Verified',"Contact Number","Email",];
$organizationDoneeArrayKeys = ["organizationName","representative",['verificationStatus','bool',['No','Yes']],"contactNumber","email",['','View','#',[],'doneeID']];



?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Individual Donees"); ?>

<?php $headerDiv->pages(["individuals", "organizations"]); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model,"Verification Status","",[ 0 => "Not Verified", 1 => "Verified"],"verificationStatusFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model,"Registered Date","registeredDate","registeredDateSort");
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>

<div class="horizontal-scroll">
    <div class="unver-user-container" id="pendingVerifications">
        <?php
        $unverified = new \app\core\components\cards\unverifiedDoneeCard();
        $unverified->displayUnverifiedDonees($donees);
        ?>
    </div>
</div>

<div id="individualDoneeDisplay" class="content">

    <?php $individualTable = new table($individualDoneeHeaders,$individualDoneeArrayKeys); ?>

    <?php

    if($donees['individuals']) {
        $individualTable->displayTable($donees['individuals']);
    } ?>

</div>

<div id="organizationDoneeDisplay" style="display: none" class="content">

    <?php $organizationTable = new table($organizationDoneeHeaders,$organizationDoneeArrayKeys); ?>

    <?php

    if($donees['organizations']) {
        $organizationTable->displayTable($donees['organizations']);
    }  ?>

</div>

<script type="module" src="/CommuSupport/public/JS/manager/donees/view.js"></script>
