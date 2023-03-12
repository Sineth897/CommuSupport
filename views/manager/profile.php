<?php

use app\core\Application;
use app\models\managerModel;

$user = Application::session()->get('user');
$manager = managerModel::getModel(['employeeID' => $user]);

?>
<!-- Import material icons to the page-->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/layout.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/profile.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/button/button-styles.css">

<div class="parent">
    <div class="profile-div">
        <div class="profile-page-container">
            <div class="profile-content-top">
                <div class="profile-picture">
                    <img src="https://www.cnet.com/a/img/resize/22b01fa0f0b66f2d66a7fa1b1306f974289bcb33/hub/2023/02/12/3e30ba7d-1fc3-44b3-ad02-85b4c69dc43e/the-flash-copy.png?auto=webp&fit=crop&height=1200&precrop=1638,920,x75,y0&width=1200"
                         alt="profile picture">
                </div>
                <div class="profile-header">
                    <h3 class="profile-name">Gineth Karu </h3>
                    <h5 class="profile-position"><span>ccManager</span><span>#887122</span></h5>
                </div>
                <div class="profile-btns">
                    <button class="btn btn-primary">Edit Profile</button>
                    <button class="btn btn-secondary">Change Password</button>
                </div>
            </div>
            <form class="profile-content-main">
                <!--                <div class="form-split">-->
                <!--                    <div class="split-item">-->
                <!--                        <label class="form-label"for="fname">First Name</label>-->
                <!--                        <input class="basic-input-field" type="text" id="fname" name="fname" value="Gineth" disabled>-->
                <!--                    </div>-->
                <!--                    <div class="split-item">-->
                <!--                        <label class="form-label"for="lname">Last Name</label>-->
                <!--                        <input class="basic-input-field" type="text" id="lname" name="lname" value="Karu" disabled>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--                <div class="form-split">-->
                <!--                    <div class="split-item">-->
                <!--                        <label class="form-label"for="fname">First Name</label>-->
                <!--                        <input class="basic-input-field" type="text" id="fname" name="fname" value="Gineth" disabled>-->
                <!--                    </div>-->
                <!--                    <div class="split-item">-->
                <!--                        <label class="form-label"for="lname">Last Name</label>-->
                <!--                        <input class="basic-input-field" type="text" id="lname" name="lname" value="Karu" disabled>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--                <div class="form-split">-->
                <!--                    <div class="split-item">-->
                <!--                        <label class="form-label"for="fname">First Name</label>-->
                <!--                        <input class="basic-input-field" type="text" id="fname" name="fname" value="Gineth" disabled>-->
                <!--                    </div>-->
                <!--                    <div class="split-item">-->
                <!--                        <label class="form-label"for="lname">Last Name</label>-->
                <!--                        <input class="basic-input-field" type="text" id="lname" name="lname" value="Karu" disabled>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--                <div class="form-grid-1">-->
                <!--                    <div class="form-group">-->
                <!--                        <label class="form-label"for="fname">First Name</label>-->
                <!--                        <input class="basic-input-field" type="text" id="fname" name="fname" value="Gineth" disabled>-->
                <!--                        <input class="basic-input-field" type="text" id="lname" name="lname" value="Karu" disabled>-->
                <!--                    </div>-->
                <!--                </div>-->
            </form>
        </div>

    </div>
    <div class="chart-div-1"></div>
    <div class="div3">
        <?php
        $retrieved_data = $manager->retrieve(["employeeID" => $user]);
        list($first_name, $last_name) = explode(" ", $retrieved_data[0]['name']);
        echo $first_name;
        echo " ";
        echo $last_name;
        ?>
    </div>

    <div class="div4"></div>

    <div class="div5"></div>

    <div class="div6"></div>
</div>

