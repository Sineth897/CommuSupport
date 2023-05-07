<link href="../public/CSS/button/button-styles.css" rel="stylesheet" >
<link href="../public/CSS/button/button-styles.css" type="text/css" rel="stylesheet" >
<link href="../public/CSS/navbar/sidenav-styles.css" type="text/css" rel="stylesheet" >
<link href="../public/CSS/table/table-styles.css" type="text/css" rel="stylesheet">

<?php
/** @var $complaints \app\models\complaintModel */

use app\core\components\tables\table;



try{
    $complaint = $complaints->allComplaints();

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

$headerDiv->heading("My Complaints");

$headerDiv->pages(['pending','completed']);

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
            <p id="sort-btn" ><i class="material-icons"  >sort</i> <span>Sort</span></p>
        </div>
    </div>

</div>


<?php
$checkVerification = new \app\core\components\layout\verificationDiv();

if($checkVerification->notVerified()) {
    return;
}
?>







<div class="content-form">


    <?php
    $headers = ['Complaint','Filed Date','Subject','Status','Solution','Reviewed Date'];
    $arrayKeys = ['complaint','filedDate','subcategoryName','status','solution','reviewedDate'];


    $complaintsTable = new table($headers,$arrayKeys);
    $complaintsTable ->displayTable($complaint);

    ?>


</div>



<div>
    <?php
    if(empty($complaint)){
        echo "No Complaints has been filed.";
    }
    ?>
</div>



<script type="module" src="../public/JS/admin/complaints/filter.js"></script>




