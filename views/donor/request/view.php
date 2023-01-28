<?php
use app\core\components\tables\table;

/** @var $model \app\models\requestModel */
/** @var $user \app\models\donorModel */



$request = $model->retrieve();
										


$header = ["PostedBy", "Approval", "ApprovedDate", "Item", "Amount","Address","Urgency","PostedDate","Notes"];

$arrayKey = ["postedBy", "approval", "approvedDate", "item", "amount","address","urgency","postedDate","notes"];

$requestTable = new table($header, $arrayKey);

$requestTable->displayTable($request);


//echo "<pre>";
//print_r($request);
//echo "</pre>";