<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/table/table-styles.css">



<?php
use app\core\components\tables\table;

/** @var $model \app\models\requestModel */
/** @var $user \app\models\adminModel */

?>
        <!--        Profile Details-->

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Requests"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
//$filter->dropDownList($model,"Community center","cc",$CCs,"ccFilter");
//$filter->dropDownList($model,"Verification Status","verificationStatus",[ "No" => "Not Verified", "Yes" => "Verified"],"verificationStatusFilter");
//$filter->dropDownList($model,"Type","type",['Individual' => 'Individual','Organization' => 'Organization'],"typeFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

//$sort = \app\core\components\form\form::begin('', '');
//$sort->checkBox($model,"Registered Date","registeredDate","registeredDateSort");
//$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>

        <!--        Content Block-->
        <div class="content">
<?php

          $requests = $model->getRequestWithPostedBy();

//          echo "<pre>";
//            print_r($request);
//            echo "</pre>";

          $header = ["PostedBy", "Status","Item",	"Amount","Posted Date"];
          $arrayKey = ["username","status","subcategoryName","amount", "postedDate",['','View','#',[],'requestID']];
          
          $requestTable = new table($header, $arrayKey);
          
          $requestTable->displayTable($requests); ?>
</div>



