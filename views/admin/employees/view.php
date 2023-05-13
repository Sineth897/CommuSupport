<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">

<?php

/**
 * @var $logistics logisticModel
 * @var $managers managerModel
 */

use app\models\logisticModel;
use app\models\managerModel;

$logisticOfficersData = $logistics->retrieveWithJoin('communitycenter','ccID');
$managersData = $managers->retrieveWithJoin('communitycenter','ccID');

$chos = \app\models\choModel::getCHOs();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Logistic Officers"); ?>

<?php $headerDiv->pages(['logistic','manager']); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($managers,"Gender","gender",['male'=>'Male','female'=>'Female'],"genderFilter");
$filter->dropDownList($managers,"District","",$chos,"choFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($managers, "Age", "age", "ageSort");
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>

<div class="content">

    <div id="logisticDiv">

        <?php

        $tableHeaders = ['Name','Age','Gender', 'Contact Number', 'Community Center'];
        $arrayKeys = ['name','age','gender','contactNumber','city',['',"View",'#',[],'employeeID']];

        $table = new app\core\components\tables\table($tableHeaders, $arrayKeys);

        $table->displayTable($logisticOfficersData);

        ?>

    </div>

    <div id="managerDiv" style="display: none">

        <?php

        $table->displayTable($managersData);

        ?>

    </div>


</div>

<script type="module" src="../public/JS/admin/employees/view.js"></script>