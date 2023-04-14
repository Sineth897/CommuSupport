<link rel="stylesheet" href="/CommuSupport/public/CSS/cards/eventcard.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">

<?php

/** @var $model \app\models\complaintModel */
/** @var $user \app\models\choModel */

use app\core\components\tables\table;
$userID = \app\core\Application::session()->get('user');
$user = $user->findOne(['filedBy'=>$userID]);
$complaint= $model->retrieve(['filedBy'=>$userID]);

if(empty($complaints)){
    echo "No Complaints has been filed.";
}

$headers = ['Filled By','Filled Date','Subject','Status','Solution','Reviewed Date'];
$arrayKeys = ['filledBy','filledDate','subject','status','solution','reviewedDate'];

$complaintsTable = new table($headers,$arrayKeys);
$complaintsTable->displayTable($complaint);



?>


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

$headerDiv->pages(["Resolved", "To Reviewed"]);

$headerDiv->end();
?>









