<?php
/** @var $inventory \app\models\inventoryModel */
/** @var $user \app\models\logisticModel */

use app\core\Application;

$user = $user->findOne(['employeeID' => Application::session()->get('user')]);
$items = $inventory->retrieveWithJoin('subcategory', 'subcategoryID', ['inventory.ccID' => $user->ccID]);
$categories = $inventory->getCategories();
$subcategories = $inventory->getsubcategories();

//for ($i = 0; $i < count($items); $i++) {
//    $items[$i]['subcategoryName'] = $subcategories[$items[$i]['itemID']];
//}


$tableHeaders = ['Item Name','Amount', 'Unit','Last Updated'];

//echo '<pre>';
//var_dump($items);
//echo '</pre>';

?>

<button id="addBtn">Add Item</button>

<div id="itemForm" style="display: none">

    <p id="resultMsg"></p>

    <?php $form = \app\core\components\form\form::begin('', ''); ?>

    <div>
        <?php $form->dropDownList($inventory, "Select a Category", '', $categories,'category'); ?>
    </div>

    <?php foreach ($categories as $key => $value): {?>
        <div id="<?php echo $key ?>" style="display: none">
            <?php $form->dropDownList($inventory, "Select an Item", 'itemID', $inventory->getsubcategories($key)); ?>
        </div>
    <?php } endforeach; ?>

    <div>
        <?php $form->inputField($inventory, 'Enter the Amount', 'number','amount', 'amount'); ?>
    </div>

    <div>
        <?php $form->button('Confirm','button', 'addToInventory'); ?>
    </div>

    <?php $form::end(); ?>

</div>



<?php $filterForm = \app\core\components\form\form::begin('', ''); ?>

<?php $filterForm->dropDownList($inventory, "Select a Category", '', $categories, 'filterCategory'); ?>

<?php $filterForm->button('Filter', 'button', 'filterBtn'); ?>

<?php $filterForm::end(); ?>

<div id="inventoryDisplay">

    <?php $inventoryTable = new \app\core\components\tables\table($tableHeaders, ['subcategoryName', 'amount', 'scale', 'updatedTime']); ?>

    <?php $inventoryTable->displayTable($items); ?>

</div>


<script src="/CommuSupport/public/JS/logistic/inventory/addItem.js" type="module"></script>