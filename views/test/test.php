<?php

$logistic = \app\models\logisticModel::getModel(['employeeID' => $_SESSION['user']]);

$inventory = \app\models\inventorylog::getInventoryLogsOfCC($logistic->ccID);

echo "<pre>";
print_r($inventory);
echo "</pre>";

?>

<div class="content">

    <?php

    $tableHeaders = ['Item', 'Amount', 'Remark', 'Date'];
    $arrayKeys = ['subcategoryName', 'amount', 'remark', 'dateReceived'];

    $inventoryLogTable = new \app\core\components\tables\table($tableHeaders, $arrayKeys);

    $inventoryLogTable->displayTable($inventory);

    ?>


</div>
