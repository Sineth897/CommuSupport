<?php

/** @var $model \app\modles\complaintModel */

use app\core\components\tables\table;

echo empty($model);

$complaints = $model->retrieve();

if(empty($complaints)){
    echo "No Complaints has been filed.";
}

$headers = ['Filled By','Filled Date','Subject','Status','Solution','Reviewed Date'];
$arraykeys = ['filedBy','filedDate','subject','status','solution','reviewedDate'];


?>

<?php $createComplaint = \app\core\components\form::begin('./cho/cc/complaint','get');?>

<button> Make a complaint </button>

<?php $createComplaint->end(); ?>

<button type="button"> Filter </button>

<div id="complaintDisplay">

    <?php $complaintsTable = new table($headers,$arraykeys); ?>

    <?php $complaintsTable->displayTable($complaints); ?>

</div>


