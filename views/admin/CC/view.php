<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">



<?php
use app\core\components\tables\table;

/** @var $model \app\models\ccModel */
/** @var $user \app\models\adminModel */

$chos = \app\models\choModel::getCHOs();

?>

<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

        <!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Community Centers");

$headerDiv->end();
?>



<?php
$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filterDivStart();

$searchDiv->filterBegin();

$filter = \app\core\components\form\form::begin('', '');
$filter->dropDownList($model,"Community Head Office","cho",$chos,"cho");
$filter->end();

$searchDiv->filterEnd();


$searchDiv->filterDivEnd();

$searchDiv->end();
?>

        <!--        Content Block-->
        <div class="content" id="ccTable">
<?php
            $userID = \app\core\Application::session()->get('user');
           // $user = $user->findOne(['adminId' => $userID]);
           $CC = $model->retrieve();


           foreach ($CC as $key => $value) {
               $CC[$key]['cho'] = $chos[$value['cho']];
           }

           $header = ["City", "Address", "Contact Number", "Community Head Office"];

           $arrayKey = ["city", "address", "contactNumber", "cho",['',"View",'#',[],'ccID']];

           $ccTable = new table($header, $arrayKey);

           $ccTable->displayTable($CC);

?>

        </div>

  <script type="module" src="../public/JS/admin/cc/view.js"></script>


