<?php
/** @var $complaints \app\models\complaintModel */
/** @var $user \app\models\choModel */

$userID = \app\core\Application::session()->get('user');

$complaint = $complaints->retrieve();


echo "<pre>";
print_r($complaint);
echo "</pre>";