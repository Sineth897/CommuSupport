<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/table/table-styles.css">
<link rel="stylesheet" href="../public/CSS/button/button-styles.css"

<?php
use app\core\components\tables\table;
/** @var $model \app\models\choModel */

?>


        <!--        Profile Details-->
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

        <!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
        <div class="heading-pages">
            <div class="heading">
                <h1>Community Head Offices</h1>
            </div>



        </div>

        <!--        Search and filter boxes -->
        <div class="search-filter">

            <div class="filters">
                <div class="filter">
                    <p><i class="material-icons">filter_list</i><span>Filter</span></p>
                </div>
                <div class="sort">
                    <p><i class="material-icons">sort</i> <span>Sort</span></p>
                </div>
            </div>

            <?php $registerCho = \app\core\components\form\form::begin('./communityheadoffices/register','get');

            $registerCho->button("Register a CHO","submit");

            $registerCho->end(); ?>


        </div>

        <!--        Content Block-->
        <div class="content">
        <?php    $communitycheadofice = $model->retrieve();

       $header = ["District", "Email", "Address", "ContactNumber"];

       $arraykey = ["district", "email", "address", "contactNumber"];

       $communitycheadoficeTable=new table($header,$arraykey);

       $communitycheadoficeTable->displayTable($communitycheadofice)
?>
        </div>

  
