<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link href="../public/CSS/button/button-styles.css" type="text/css" rel="stylesheet" >
<link href="../public/CSS/navbar/sidenav-styles.css" type="text/css" rel="stylesheet" >
<link href="../public/CSS/table/table-styles.css" type="text/css" rel="stylesheet">

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Complaints");

$headerDiv->pages(['pending','completed']);

$headerDiv->end();
?>

<?php
$checkVerification = new \app\core\components\layout\verificationDiv();

if($checkVerification->notVerified()) {
    return;
}
?>


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

?>

<div class="content-form">

    <?php
    $headers = ['Filed By','Filed Date','Subject','Status','Solution','Reviewed Date'];
    $arrayKeys = ['filedBy','filedDate','subject','status','solution','reviewedDate'];


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


$headers = ['Filed By','Filed Date','Subject','Status','Solution','Reviewed Date'];
$arrayKeys = ['filedBy','filedDate','subject','status','solution','reviewedDate'];


$complaintsTable = new table($headers,$arrayKeys);
$complaintsTable ->displayTable($complaint);

if(empty($complaint)){
    echo "No Complaints has been filed.";
}

?>



