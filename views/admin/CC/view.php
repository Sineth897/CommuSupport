<?php
use app\core\components\tables\table;

/** @var $model \app\models\ccModel */
/** @var $user \app\models\adminModel */

$userID = \app\core\Application::session()->get('user');
// $user = $user->findOne(['adminId' => $userID]);
$CC = $model->retrieve();

$header = ["Address", "City", "Email", "Fax", "Contact Number", "CommunityHeadOfficers"];

$arrayKey = ["address", "city", "email", "fax", "contactNumber", "cho"];

$ccTable = new table($header, $arrayKey);

$ccTable->displayTable($CC);

// echo "<pre>";
// print_r($CC);
// echo "</pre>";