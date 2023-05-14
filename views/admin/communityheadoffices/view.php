<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">

<?php
use app\core\components\tables\table;
/** @var $model \app\models\choModel */

?>


        <!--        Profile Details-->
<?php $profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end(); ?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Community Head Offices");

$headerDiv->end();
?>

        <!--        Search and filter boxes -->
<?php
$searchDiv = new \app\core\components\layout\searchDiv();

    $registerCho = \app\core\components\form\form::begin('./communityheadoffices/register','get');

    $registerCho->button("Register a CHO","submit");

    $registerCho->end();

    $searchDiv->end();
?>



        <!--        Content Block-->
        <div class="content">
        <?php    $communitycheadofice = $model->retrieve();

       $header = ["District", "Email", "Address", "Contact Number"];

       $arraykey = ["district", "email", "address", "contactNumber",['',"View",'#',[],'choID']];

       $communitycheadoficeTable=new table($header,$arraykey);

       $communitycheadoficeTable->displayTable($communitycheadofice)
?>
        </div>

  
<script src="../public/JS/admin/cho/view.js" type="module"></script>