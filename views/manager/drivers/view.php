<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<?php

/** @var $model \app\models\driverModel */
/** @var $user \app\models\managerModel */

use app\core\components\tables\table;

$user = \app\models\managerModel::getModel(['employeeID' => $_SESSION['user']]);

$drivers = $model->retrieve(['ccID' => $user->ccID]);

if( empty($drivers) ) {
    echo "No drivers currently registered";
}

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Drivers"); ?>

<?php $creatEvent = \app\core\components\form\form::begin('./drivers/register', 'get'); ?>

<?php $creatEvent->end(); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv(); ?>

<?php $searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model,"Vehicle Type","vehicleType",$model->getVehicleTypes(),"vehicleTypeFilter");
$filter->dropDownList($model,"Preference","preference",$model->getPreferences(),"preferenceFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model,"Age","age","ageSort");
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

echo "<div class='btn-together'>";

$searchDiv->search();

echo  "<button class='btn-primary'> Register a driver </button>
    <a class='btn-primary' href='./drivers/stat'> View Driver Statistics </a>
</div>";

$searchDiv->end(); ?>

<div id="driverTable"  class="content">

    <?php

    $headers = ['Name','Contact Number','Vehicle', 'Vehicle Number', 'Preference'];
    $arraykeys= ['name','contactNumber','vehicleType', 'vehicleNo', 'preference',['','View','#',[],'employeeID']];

    $driversTable = new table($headers,$arraykeys);

    $driversTable->displayTable($drivers); ?>

</div>

<script type="module" src="../public/JS/manager/drivers/view.js"></script>


