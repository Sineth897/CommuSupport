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

<?php $createComplaint = \app\core\components\form::begin('./cho/cc/complaint','get');?>

<button> Make a complaint </button>

<?php $createComplaint->end(); ?>

<button type="button"> Filter </button>

<div id="complaintDisplay">

    <?php $complaintsTable = new table($headers,$arrayKeys); ?>

    <?php $complaintsTable->displayTable($complaint); ?>

</div>








