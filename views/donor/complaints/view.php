<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link href="../public/CSS/button/button-styles.css" type="text/css" rel="stylesheet" >
<link href="../public/CSS/navbar/sidenav-styles.css" type="text/css" rel="stylesheet" >
<link href="../public/CSS/table/table-styles.css" type="text/css" rel="stylesheet">
<?php
/** @var $complaints \app\models\complaintModel */

use app\core\components\tables\table;

$userID = \app\core\Application::session()->get('user');

try{
    $complaint = $complaints->getOwnComplaints($userID);

}
catch(\Exception $e){
    echo $e->getMessage();
}


//$pending= array_filter($complaint,function ($complaint){
//    return $complaint['status'] === "pending" ;
//})




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


<div class="search-filter">
    <div class="pending" id="pending">
        <p id="pending"><i class="material-icons">pending </i><span>Pending</span> </p>
    </div>

    <div class="sort" id="sort-btn">
        <p id="sort-btn" ><i class="material-icons"  >sort</i> <span>Sort by Reviewed Date</span></p>
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



<script type="module" src="../public/JS/donor/complaint/filter.js"></script>






