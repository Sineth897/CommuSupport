<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="../public/CSS/table/table-styles.css">



<?php
use app\core\components\tables\table;

/** @var $model \app\models\requestModel */
/** @var $user \app\models\adminModel */

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
                <h1>Requests</h1>
            </div>
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
            <div class="search">
                <input type="text" placeholder="Search">
                <a href="#"><i class="material-icons">search</i></a>
            </div>

        </div>

        <!--        Content Block-->
        <div class="content">
<?php
          $userID = \app\core\Application::session()->get('user');
          // $user = $user->findOne(['adminId' => $userID]);
          $request = $model->retrieve();
          
          $header = [	"RequestID","PostedBy",	"Approval",	"ApprovedDate",	"Item",	"Amount","PostedDate"];
          
          $arrayKey = ["requestID","postedBy","approval",	"approvedDate",	"item",	"amount", "postedDate"];
          
          $requestTable = new table($header, $arrayKey);
          
          $requestTable->displayTable($request); ?> 
</div>



