<link rel="stylesheet" href="../public/CSS/button/button-styles.css">

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

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


<!--        Search and filter boxes -->
<?php
$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$searchDiv->sortEnd();

$searchDiv->end();
?>


<?php

/** @var $complaint \app\models\complaintModel */
/** @var $user \app\models\donorModel */

use app\core\components\tables\table;

$userID = \app\core\Application::session()->get('user');
try{
    $complaint = $complaint->ownComplaints($userID);

}
catch(\Exception $e){
    echo $e->getMessage();
}

//$complaints= $model->retrieve(['filedBy'=>'complaintID']);

$headers = ['Filed Date','Subject','Status','Solution','Reviewed Date'];
$arrayKeys = ['filedDate','subject','status','solution','reviewedDate'];


$complaintsTable = new table($headers,$arrayKeys);
$complaintsTable ->displayTable($complaint);

if(empty($complaint)){
    echo "No Complaints has been filed by the user. ";
}

?>

