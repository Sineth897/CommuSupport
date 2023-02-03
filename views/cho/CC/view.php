<?php

/** @var $model \app\models\ccModel */
/** @var $user \app\models\choModel */

$userID = \app\core\Application::session()->get('user');
//$user = $user->findOne(['choID' => $userID]);
$CC = $model->retrieve(['choID' => $userID]);


echo "<pre>";
print_r($CC);
echo "</pre>";

?>
<body>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="/CommuSupport/public/css/layout.css">
<link rel="stylesheet" href="main-styles.css">
<link rel="stylesheet" href="sidenav-styles.css">


<di class="container">
    <div class="sidenav">
        <div class="logo_content">
            <div class="logo">
                <img src="logo.svg" alt="" class="logo_name">
            </div>
            <i class="material-icons" id="btn">menu</i>
        </div>
        <ul class="nav_list">
            <li>
                <a href="#">
                    <i class="material-icons">summarize</i>
                    <span class="links_name">Requests</span>
                </a>
                <span class="tooltip">Requests</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">today</i>
                    <span class="links_name">Events</span>
                </a>
                <span class="tooltip">Events</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">inventory_2</i>
                    <span class="links_name">Donations</span>
                </a>
                <span class="tooltip">Donations</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">report_gmailerrorred</i>
                    <span class="links_name">Complaints</span>
                </a>
                <span class="tooltip">Complaints</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">emoji_people</i>
                    <span class="links_name">Donees</span>
                </a>
                <span class="tooltip">Donees</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">accessibility_new</i>
                    <span class="links_name">Donors</span>
                </a>
                <span class="tooltip">Donors</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">local_shipping</i>
                    <span class="links_name">Drivers</span>
                </a>
                <span class="tooltip">Drivers</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">local_shipping</i>
                    <span class="links_name">Deliveries</span>
                </a>
                <span class="tooltip">Deliveries</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">badge</i>
                    <span class="links_name">Employees</span>
                </a>
                <span class="tooltip">Employees</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">emoji_transportation</i>
                    <span class="links_name">Community Center</span>
                </a>
                <span class="tooltip">Community Center</span>
            </li>
            <li>
                <a href="#">
                    <i class="material-icons">home_work</i>
                    <span class="links_name">Community Head Office</span>
                </a>
                <span class="tooltip">Community Head Office</span>
            </li>


        </ul>
        <div class="logout">
            <a href="#"><i class="material-icons" id="log_out">power_settings_new</i></a>
        </div>
    </div>

    <div class="main">
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
                <h1>Heading</h1>
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
            <div class="filler"></div>
        </div>

    </div>
</di>

<script src="sidebar-scripts.js"></script>
</body>