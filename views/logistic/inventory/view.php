<link rel="stylesheet" href="../public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css" >
<?php
/** @var $inventory \app\models\inventoryModel */
/** @var $user \app\models\logisticModel */

use app\core\Application;

$user = $user->findOne(['employeeID' => Application::session()->get('user')]);
$items = $inventory->retrieveWithJoin('subcategory', 'subcategoryID', ['inventory.ccID' => $user->ccID]);
$categories = $inventory->getCategories();
$subcategories = $inventory->getsubcategories();

$tableHeaders = ['Item Name','Category','Amount', 'Unit','Last Updated'];
$arrayKeys = ['subcategoryName', 'categoryName', 'amount', 'scale', 'updatedTime'];

for($i = 0; $i < count($items); $i++) {
    $items[$i]['categoryName'] = $categories[$items[$i]['categoryID']];
}

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Inventory"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filterForm = \app\core\components\form\form::begin('', '');
$filterForm->dropDownList($inventory, "Select a Category", '', $categories, 'filterCategory');
$filterForm::end();

$searchDiv->filterEnd();

$searchDiv->sortBegin();

$sortForm = \app\core\components\form\form::begin('', '');
$sortForm->checkBox($inventory,"Last Updated","",'sortLastUpdated');
$sortForm->checkBox($inventory, "Amount", "amount", 'sortAmount');
$sortForm::end();

$searchDiv->sortEnd();

$searchDiv->filterDivEnd(); ?>

<button id="addBtn" class="btn-cta-primary">Add Item</button>

<?php $searchDiv->end(); ?>



<div id="itemForm" class="popup-background">

    <div class="popup" id="logistic-item-add-popup-form">

        <?php $form = \app\core\components\form\form::begin('', ''); ?>

        <?php $form->formHeader('Add Item'); ?>

        <div>
            <?php $form->dropDownList($inventory, "Select a Category", '', $categories,'category'); ?>
        </div>

        <?php foreach ($categories as $key => $value): {?>
            <div id="<?php echo $key ?>" style="display: none">
                <?php $form->dropDownList($inventory, "Select an Item", 'subcategoryID', $inventory->getsubcategories($key)); ?>
            </div>
        <?php } endforeach; ?>

        <div>
            <?php $form->inputField($inventory, 'Enter the Amount', 'number','amount', 'amount'); ?>
        </div>

        <div>
            <?php $form->button('Confirm','button', 'addToInventory'); ?>
        </div>

        <?php $form::end(); ?>

        <div class="close" id="closeBtnDiv">
            <i class="material-icons">close</i>
        </div>


    </div>

</div>

<div id="inventoryDisplay" class="content">

    <?php $inventoryTable = new \app\core\components\tables\table($tableHeaders, $arrayKeys); ?>

    <?php $inventoryTable->displayTable($items); ?>

</div>


<script src="/CommuSupport/public/JS/logistic/inventory/addItem.js" type="module"></script>