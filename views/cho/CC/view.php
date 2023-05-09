<!-- import css styles for tables and buttons -->

<link href="../public/CSS/table/table-styles.css" type="text/css" rel="stylesheet" >
<link href="../public/CSS/button/button-styles.css" type="text/css" rel="stylesheet" >

<?php

//import cc and cho models
/** @var $model \app\models\ccModel */
/** @var $user \app\models\choModel */

//getting the userID for the current session
$userID = \app\core\Application::session()->get('user');
//calling the getAll function for the current session
$CC = $model->getAll($userID);

?>

<?php
// importing profile component
$profile = new \app\core\components\layout\profileDiv();

$profile->profile();

$profile->notification();

$profile->end();
?>

<!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Community Centers ");

$headerDiv->end();
?>

     <!--   Heading Block - Other Pages for Ongoing, Completed .etc      -->
<div class="content">
            <div class="register">
                <form method="get" action="../cho/communitycenter/register">
                    <button class="btn-cta-primary" > Register CC </button>
                </form>
            </div>

        </div>

        <!--        Search and filter boxes -->
        <div class="search-filter">
            <div class="search">
                <input type="text" placeholder="Search" name="find" id="search">
                <a href="#"><i class="material-icons">search</i></a>
            </div>

        </div>

        <!--        Content Block-->

            <?php

            $headers=['City','Email','Fax','Contact Number','Manager','Logistic Officer'];
            $arrayKeys=['city','email','fax','contactNumber',['manager','Add','./communitycenters/register/manager',['ccID']],['logistic','Add','./communitycenters/register/logistic',['ccID']]];
            //create a table from table component
            $ccTable = new \app\core\components\tables\table($headers,$arrayKeys);
            //passing data from $CC to create a table
            $ccTable->displayTable($CC);
            ?>

        </div>

<div>

</div>


<script type="module" src="../public/JS/cho/cc/search.js"></script>