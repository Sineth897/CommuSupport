<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">


<?php

$profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end();
?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Complaints");

$headerDiv->end();
?>


<?php

/** @var $complaints \app\models\complaintModel */
/** @var $user \app\models\choModel */

use app\core\components\tables\table;

$userID = \app\core\Application::session()->get('user');
//
try{
    $complaint = $complaints->getComplaints($userID);

}
catch(\Exception $e){
    echo $e->getMessage();
}

//$complaints= $model->retrieve(['filedBy'=>'complaintID']);

if(empty($complaint)){
    echo "No Complaints has been filed.";
}

$headers = ['Filed By','Filed Date','Subject','Status','Solution','Reviewed Date'];
$arrayKeys = ['filedBy','filedDate','subject','status','solution','reviewedDate'];


$complaintsTable = new table($headers,$arrayKeys);
$complaintsTable ->displayTable($complaint);




?>














