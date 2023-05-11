<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Inventory Log");

$headerDiv->end();
?>

<div class="content">

    <?php

    $logs = \app\models\inventorylog::getInventoryLog();

    $tableHeaders = ['Date', 'Item', 'Quantity',"Community center" ,'Remark',];
    $tableKeys = ['dateReceived', 'subcategoryName', 'amount','city' ,'remark',];

    $table = new app\core\components\tables\table($tableHeaders, $tableKeys);

    $table->displayTable($logs);

    ?>

</div>
