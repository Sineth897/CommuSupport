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

$individualDoneeHeaders = ["First Name","Last Name","Age","Contact Number","Email","Address"];
$individualDoneeArrayKeys = ["fname","lname","age","contactNumber","email","address"];

$organizationDoneeHeaders = ["Organization Name","Representative Name","Contact Number","Email","Address"];
$organizationDoneeArrayKeys = ["organizationName","representative","contactNumber","email","address"];



?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

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

//$searchDiv->search();

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
    } else {
        echo "No Individual Donees";
    } ?>

</div>

<div id="organizationDoneeDisplay" style="display: none" class="content">

    <?php $organizationTable = new table($organizationDoneeHeaders,$organizationDoneeArrayKeys); ?>

    <?php

    if($donees['organizations']) {
        $organizationTable->displayTable($donees['organizations']);
    } else {
        echo "No Organizations";
    } ?>

</div>

<script type="module" src="../public/JS/manager/donees/view.js"></script>
