
<?php

/**
 *@var $donee doneeModel
 */

use app\models\doneeModel;

$doneeInfo =  $donee->getDoneeInformationForProfile();

echo '<pre>';
var_dump($doneeInfo['doneeStat']);
echo '</pre>';

?>
