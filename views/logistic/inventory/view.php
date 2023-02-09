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

<div class="profile">
    <div class="notif-box">
        <i class="material-icons">notifications</i>
    </div>
    <div class="profile-box">
        <div class="name-box">
            <h4>Username</h4>
            <p>Position</p>
        </div>
        <div class="profile-img">
            <img src="https://www.w3schools.com/howto/img_avatar.png" alt="profile">
        </div>
    </div>
</div>

<?php $headerDiv = new \app\core\components\layout\headerDiv(); ?>

<?php $headerDiv->heading("Inventory"); ?>

<?php $headerDiv->end(); ?>

<?php $searchDiv = new \app\core\components\layout\searchDiv(); ?>

<?php $searchDiv->filters(); ?>

<button id="addBtn" class="btn-cta-primary">Add Item</button>

<?php $searchDiv->end(); ?>



<div id="itemForm" class="popup-background">

    <div class="popup" id="logistic-item-add-popup-form">

        <span id="resultMsg" class="error"></span>

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


<?php $filterForm = \app\core\components\form\form::begin('', ''); ?>

<?php $filterForm->dropDownList($inventory, "Select a Category", '', $categories, 'filterCategory'); ?>

<?php $filterForm->button('Filter', 'button', 'filterBtn'); ?>

<?php $filterForm::end(); ?>

<div id="inventoryDisplay">

    <?php $inventoryTable = new \app\core\components\tables\table($tableHeaders, $arrayKeys); ?>

    <?php $inventoryTable->displayTable($items); ?>

</div>


<script src="/CommuSupport/public/JS/logistic/inventory/addItem.js" type="module"></script>