<?php

/** @var $model \app\models\ccModel */
/** @var $user \app\models\choModel */

$userID = \app\core\Application::session()->get('user');


$CC = $model->getAll($userID);

?>
        <link href="../public/CSS/table/table-styles.css" type="text/css" rel="stylesheet" >
        <link href="../public/CSS/button/button-styles.css" type="text/css" rel="stylesheet" >
<?php $profile = new \app\core\components\layout\profileDiv();

$profile->notification();

$profile->profile();

$profile->end(); ?>

     <!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
        <div class="heading-pages">
            <div class="heading">
                <h1>Community Centers </h1>
            </div>
            <div class="register">
                <form method="get" action="../cho/communitycenter/register">
                    <button class="btn-cta-primary" > Register CC </button>
                </form>

            </div><!--
            <div class="pages">
                <a href="#">
                    <i class="material-icons">cached</i>
Ongoing</a>
                <a href="#">
                    <i class="material-icons">check_circle_outline</i>
Completed</a>
                <a href="#">
                    <i class="material-icons">block</i>
Cancelled</a>
            </div>-->
        </div>

        <!--        Search and filter boxes -->
        <div class="search-filter">
        <!--
            <div class="filters">
                <div class="filter">
                    <p><i class="material-icons">filter_list</i><span>Filter</span></p>
                </div>
                <div class="sort">
                    <p><i class="material-icons">sort</i> <span>Sort</span></p>
                </div>
            </div>  -->

            <div class="search">
                <input type="text" placeholder="Search" name="find">
                <a href="#"><i class="material-icons">search</i></a>
            </div>

        </div>

        <!--        Content Block-->
        <div class="content">
            <?php

            $headers=['City','Email','Fax','Contact Number','Manager','Logistic Officer'];
            $arrayKeys=['city','email','fax','contactNumber',['manager','Add','./communitycenters/register/manager',['ccID']],['logistic','Add','./communitycenters/register/logistic',['ccID']]];
            $ccTable = new \app\core\components\tables\table($headers,$arrayKeys);
            $ccTable->displayTable($CC);
            ?>

        </div>

<div>

</div>