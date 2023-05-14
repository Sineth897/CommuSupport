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
$complaint = $complaints->getAllComplaints($userID);

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

$headerDiv->heading("Complaints by Donors and Donees ");

$headerDiv->end();
?>

<?php

$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv ->filterDivStart();
//filter complaints according to pending and completed
$searchDiv->filterBegin();
$filterForm = \app\core\components\form\form::begin('', '');
$filterForm->dropDownList($complaints, "Select a Status", '',['pending'=>'Pending','completed'=>"Completed"], 'filterCategory');
$filterForm::end();
$searchDiv->filterEnd();


// sort by reviewed date
$searchDiv->sortBegin();
$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($complaints,"Reviewed Date","reviewedDate","registeredDateSort");
$sort->end();
$sort::end();

$searchDiv->sortEnd();
$searchDiv->filterDivEnd();
$searchDiv->end();


?>

<div class="content">

    <?php

    $headers = ['Filed By','Filed Date','Subject','Status','Solution','Reviewed Date'];
    $arrayKeys = ['username','filedDate','subject','status',['solution','Add Solution','./complaints/solution',['complaintID','filedBy']],'reviewedDate'];

   // creating a new table for display complaints
    $complaintsTable = new table($headers,$arrayKeys);
    $complaintsTable ->displayTable($complaint);

    ?>

    <div class="no-complaint">
        <?php
        // if there are no complaints filed
        if(empty($complaint)){
            echo "No Complaints has been filed.";
        }
        ?>

    </div>



</div>


<!---->
<script type="module" src="../public/JS/cho/complaints/sort.js"></script>















