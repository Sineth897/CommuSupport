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

<?php

$searchDiv = new \app\core\components\layout\searchDiv();
$searchDiv ->filterDivStart();

$searchDiv->sortBegin();

$sort = \app\core\components\form\form::begin('', '');
$sort->checkBox($complaints,"Reviewed Date","reviewedDate","registeredDateSort");
$sort->end();
$sort::end();


$searchDiv->sortEnd();
$searchDiv->filterDivEnd();
$searchDiv->end();


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



<script type="module" src="../public/JS/admin/complaints/sort.js"></script>




