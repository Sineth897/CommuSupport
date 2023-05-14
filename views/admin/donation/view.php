<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">



<?php
use app\core\components\tables\table;

/** @var $model \app\models\donationModel */
/** @var $user \app\models\adminModel */


?>
        <!--        Profile Details-->
<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Donations"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model,"Community center","cc",\app\models\ccModel::getCCs(),"ccFilter");
$filter->dropDownList($model,"Item",'item',\app\models\donationModel::getAllSubcategories(),"subcategoryFilter");
//$filter->dropDownList($model,"Type","type",['Individual' => 'Individual','Organization' => 'Organization'],"typeFilter");
$filter->end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($model,"Date","date","dateSort");
$sort->end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd();

$searchDiv->search();

$searchDiv->end(); ?>

        <!--        Content Block-->
        <div class="content" id="donationTable">
<?php

           $donation = $model->getDonationWithPostedBy();

           $header = ["Create By", "Item", "Amount", "Date", "Donate To","Delivery Status"];

           $arrayKey = ["username", "subcategoryName", "amount", "date", "city","deliveryStatus",['','View','#',[],'donationID']];

           $donationTable = new table($header, $arrayKey);

           $donationTable->displayTable($donation);

?>
        </div>

<script type="module" src="../public/JS/admin/donation/view.js"></script>
    


