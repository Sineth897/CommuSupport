<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<?php

?>

<style>

    @media print {

        .sidenav, .profile, .search-filter {
            display: none;
        }

        .main {
            width: 100vw;
            left: 0;
        }

        .heading-pages {
            justify-content: center;
        }

    }

</style>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Inventory Log");

$headerDiv->end();
?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

echo "<button class='btn-cta-primary'>Print</button>";

$searchDiv->end(); ?>

<div class="content">

    <?php

    $logs = \app\models\inventorylog::getInventoryLog();

    $tableHeaders = ['Date', 'Item', 'Quantity',"Community center" ,'Remark',];
    $tableKeys = ['dateReceived', 'subcategoryName', 'amount','city' ,'remark',];

    $table = new app\core\components\tables\table($tableHeaders, $tableKeys);

    $table->displayTable($logs);

    ?>

</div>

<script>

    window.onload = function(){
        document.querySelector("button.btn-cta-primary").addEventListener("click", function(){
            window.print();
        })
    }


</script>
