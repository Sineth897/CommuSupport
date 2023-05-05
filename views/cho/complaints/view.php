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

$headerDiv->heading("Complaints by Donors and Donees ");

$headerDiv->end();
?>


<div class="content">
    <div class="filters">

            <p ><i class="material-icons"  >
                    <select id="filter">
                        <option value="all">All</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select></i>
                <span>Filter</span>
            </p>

        <div class="sort" id="sort-btn">
            <p id="sort-btn" ><i class="material-icons"  >sort</i> <span>Sort by Reviewed Date</span></p>
        </div>
    </div>

</div>
    <?php
$headers = ['Filed By','Filed Date','Subject','Status','Solution','Reviewed Date'];
$arrayKeys = ['username','filedDate','subcategoryName','status',['solution','Add Solution','./complaints/solution',['complaintID']],'reviewedDate'];


$complaintsTable = new table($headers,$arrayKeys);
$complaintsTable ->displayTable($complaint);

?>

<div class="no-complaint">
    <?php
    if(empty($complaint)){
        echo "No Complaints has been filed.";
    }
    ?>

</div>


<script type="module" src="../public/JS/cho/complaints/sort.js"></script>















