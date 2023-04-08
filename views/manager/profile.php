<?php

use app\core\Application;
use app\models\managerModel;
use app\models\ccModel;


$user = Application::session()->get('user');
$manager = managerModel::getModel(['employeeID' => $user]);


$retrieved_data = $manager->retrieve(["employeeID" => $user]);
list($first_name, $last_name) = explode(" ", $retrieved_data[0]['name']);
//var_dump($retrieved_data);
$manager_cc = ccModel::getModel(['ccID' => ($retrieved_data[0]['ccID'])]);
$manager_cc_details = $manager_cc->retrieve(['ccID' => ($retrieved_data[0]['ccID'])]);
//var_dump($manager_cc_details);
$NIC = $retrieved_data[0]['NIC'];
?>
<!-- Import material icons to the page-->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/profile.css">

<link rel="stylesheet" href="/CommuSupport/public/CSS/layout.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/cards/delivery-card-log.css">

<div class="parent">
    <div class="profile-div">
        <div class="profile-page-container">
            <div class="profile-content-top">
                <div class="profile-picture">
                    <img src="https://www.cnet.com/a/img/resize/22b01fa0f0b66f2d66a7fa1b1306f974289bcb33/hub/2023/02/12/3e30ba7d-1fc3-44b3-ad02-85b4c69dc43e/the-flash-copy.png?auto=webp&fit=crop&height=1200&precrop=1638,920,x75,y0&width=1200"
                         alt="profile picture">
                </div>
                <div class="profile-header">
                    <h3 class="profile-name"><?php
                        echo $first_name;
                        echo " ";
                        echo $last_name;
                        ?></h3>
                    <h5 class="profile-position"><span>
                           Manager for  <?php
                            echo $manager_cc_details[0]['city'];
                            ?> Community Center
                        </span></h5>
                </div>
                <div class="profile-btns">
                    <button class="btn-small-primary">Edit Profile</button>
                    <button class="btn-small-primary">Change Password</button>
                </div>
            </div>
            <form class="form-grid-2-2">
                <div class="form-group">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="basic-input-field" id="fname" disabled value="<?php echo $first_name ?>">
                </div>
                <div class="form-group">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="basic-input-field" id="lname" disabled value="<?php echo $last_name ?>">
                </div>
                <div class="form-group">
                    <label for="nic" class="form-label">NIC</label>
                    <input type="text" class="basic-input-field" id="nic" disabled value="<?php echo $NIC ?>">
                </div>
                <div class="form-group">
                    <label for="age" class="form-label">Age</label>
                    <input type="text" class="basic-input-field" id="age" disabled
                           value="<?php echo $retrieved_data[0]['age'] ?>">
                </div>
                <div class="form-group">
                    <label for="gender" class="form-label">Gender</label>
                    <input type="text" class="basic-input-field" id="gender" disabled
                           value="<?php echo $retrieved_data[0]['gender'] ?>">
                </div>
                <div class="form-group">
                    <label for="contactNo" class="form-label">Contact Number</label>
                    <input type="text" class="basic-input-field" id="contactNo" disabled
                           value="<?php echo $retrieved_data[0]['contactNumber'] ?>">
                </div>
            </form>
            <form action="" class="form-grid-1">
                <div class="form-group">
                    <label for="address-line-1" class="form-label">Personal Address</label>
                    <input type="text" class="basic-input-field" id="address-line-1" disabled
                           value="<?php echo $retrieved_data[0]['address'] ?>">
                </div>
                <div class="form-group">
                    <label for="address-line-1" class="form-label">CC Address</label>
                    <input type="text" class="basic-input-field" id="address-line-1" disabled
                           value="<?php echo $manager_cc_details[0]['address'] ?>">
                </div>
            </form>
        </div>

    </div>
    <div class="chart-div-1">
<!--        Counts-->
        <div class="stat-block positive">
            <p>78 <i class="material-icons">arrow_upward</i></p>
            <p>Registered Users</p>
        </div>
        <div class="stat-block positive">
            <p>78</p>
            <p>Registered Drivers</p>
        </div>
        <div class="stat-block negative">
            <p>400<i class="material-icons">arrow_downward</i></p>
            <p>Donations within CC</p>
        </div>
        <div class="stat-block negative">
            <p>78<i class="material-icons">arrow_downward</i></p>
            <p>Registered Users</p>
        </div>
    </div>

    <div class="div4"></div>

    <div class="div5"></div>
</div>

