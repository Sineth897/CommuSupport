<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/complaints/complaint.css">

<?php

/** @var $complaints \app\models\complaintModel */
/** @var $user \app\models\choModel */

use app\core\components\tables\table;

$userID = \app\core\Application::session()->get('user');
//
try{
    $complaint = $complaints->getAllComplaints($userID);

}
catch(\Exception $e){
    echo $e->getMessage();
}
?>

<?php

$profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end();
?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Complaints");

$headerDiv->end();
?>
<?php
$filterDiv = new \app\core\components\layout\searchDiv();

$filterDiv->filterBegin();

//$filter = \app\core\components\form\form::begin('', '');
//$filter->dropDownList($model,"Event Type","eventCategory",$model->getEventCategories(),"eventCategory");
//$filter->end();

$filterDiv->filterEnd();

$filterDiv->sortBegin();

$sortForm = \app\core\components\form\form::begin('', '');

$sortForm->checkBox($complaints,"Sort By Date","reviewedDate",'filter');

$sortForm::end();

$filterDiv->sortEnd();

$filterDiv->filterDivEnd();


?>



<div class="content">
<?php
$headers = ['Filed By','Filed Date','Subject','Status','Solution','Reviewed Date'];
$arrayKeys = ['username','filedDate','subcategoryName','status',['solution','Add Solution','./complaints/solution',['complaintID']],'reviewedDate'];


$complaintsTable = new table($headers,$arrayKeys);
$complaintsTable ->displayTable($complaint);

?>
</div>
<div class="no-complaint">
    <?php
    if(empty($complaint)){
        echo "No Complaints has been filed.";
    }
    ?>

</div>


<script type="module" src="../public/JS/cho/event/filter.js"></script>















