<?php
use app\core\components\tables\table;

/** @var $model \app\models\requestModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$request = $model->retrieve();

$header = [	"RequestID","PostedBy",	"Approval",	"ApprovedDate",	"Item",	"Amount","Address", "Urgency", "PostedDate", "Notes"];

$arrayKey = ["requestID","postedBy","approval",	"approvedDate",	"item",	"amount","address", "urgency", "postedDate", "notes"];

$requestTable = new table($header, $arrayKey);

$requestTable->displayTable($request);


//echo "<pre>";
//print_r($request);
//echo "</pre>";