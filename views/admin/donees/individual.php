<?php
/** @var $model \app\models\doneeModel */
/**  @userID string */

$doneeID = $_GET['doneeID'];
?>

<link rel="stylesheet" href="/CommuSupport/public/CSS/individual/donee.css">


<div class="profile-container">
    <div class="stat-card req">
        <div class="main-stat">
            <div class="stat-title">
                Total Requests
            </div>
            <div class="main-val stat-value">
                <p>60</p>
            </div>
        </div>
        <div class="sec-stat first">
            <div class="stat-title">
                Pending Requests
            </div>
            <div class="sec-val stat-value">
                <p>45</p>
            </div>
        </div>
        <div class="sec-stat second">
            <div class="stat-title">
                Accepted Requests
            </div>
            <div class="sec-val stat-value">
                <p>45</p>
            </div>
        </div>
    </div>
    <div class="stat-card donation">
        <div class="main-stat">
            <div class="stat-title">
                Donations
            </div>
            <div class="main-val stat-value">
                <p>60</p>
            </div>
        </div>
        <div class="sec-stat first">
            <div class="stat-title">
                Donations
            </div>
            <div class="sec-val stat-value">
                <p>Rice</p>
            </div>
        </div>
        <div class="sec-stat second">
            <div class="stat-title">
                Accepted Requests
            </div>
            <div class="sec-val stat-value">
                <p>45</p>
            </div>
        </div>
    </div>
    <div class="stat-card event"> Events Participated</div>
    <div class="profile-block">
        <?php
        $doneeData = $model->getDoneePersonalInfo($doneeID);

        // array(1) { [0]=> array(18) { ["doneeID"]=> string(23) "donee6384c832a74500.891" ["registeredDate"]=> string(10) "2022-11-28" ["verificationStatus"]=> int(1) ["email"]=> string(18) "oshani99@gmail.com" ["address"]=> string(26) "22,Kalapaluwawa,Rajagiriya" ["contactNumber"]=> string(10) "0714852365" ["type"]=> string(10) "Individual" ["mobileVerification"]=> int(1) ["longitude"]=> float(0) ["latitude"]=> float(0) ["doneeName"]=> string(14) "Oshani Nimeshi" ["NIC"]=> string(12) "198835752589" ["age"]=> int(34) ["regNo"]=> NULL ["representative"]=> NULL ["representativeContact"]=> NULL ["capacity"]=> NULL ["communityCenterName"]=> string(10) "Wallawatta" } }
        ?>
        <h><?php echo $doneeData[0]['doneeName'] ?></h>
        <!-- check the donee type and use it in the name -->
        <?php if ($doneeData[0]['type'] == 'Individual') { ?>

            <p class="profile-category">Individual</p>
            <?php
        } else { ?>

            <p class="profile-category">
                Organization
            </p>
        <?php } ?>

        <?php if ($doneeData[0]['type'] == 'Organization') { ?>
            <h3 class="profile-org-rep">
                <?php echo $doneeData[0]['representative'] ?>
            </h3>
            <p class="org-rep-label">
                Representative
            </p>
            <?php
        } ?>

        <h3 class="profile-contact">
            <?php echo $doneeData[0]['contactNumber'] ?>
        </h3>

        <p>Contact</p>

        <h3 class="profile-email">
            <?php echo $doneeData[0]['email'] ?>
        </h3>

        <p>Email</p>

        <h3 class="profile-address">
            <?php echo $doneeData[0]['address'] ?>
        </h3>

        <p>Address</p>

        <h3 class="profile-cc">
            <?php echo $doneeData[0]['communityCenterName'] ?>
        </h3>

        <p>Community Center</p>

        <h3 class="profile-ver-status">
            <?php
            if ($doneeData[0]['verificationStatus'] == 1) {
                echo "Verified";
            } else {
                echo "Not Verified";
            }
            ?>
        </h3>

        <p>Verification Status</p>

        <a href="/CommuSupport/src/donee/<?php echo $doneeID ?>front.pdf" class="profile-view-docs" target="_blank">
            Front
        </a>
        <a href="/CommuSupport/src/donee/<?php echo $doneeID ?>back.pdf" class="profile-view-docs" target="_blank">
            Back
        </a>

    </div>
    <div class="user-activity">
        <p>
            User Activity
        </p>
        <div class="activity-scroller">
            <div class="activity-card"></div>
            <div class="activity-card"></div>
            <div class="activity-card"></div>
            <div class="activity-card"></div>
            <div class="activity-card"></div>
            <div class="activity-card"></div>
            <div class="activity-card"></div>
            <div class="activity-card"></div>
            <div class="activity-card"></div>
            <div class="activity-card"></div>
        </div>
    </div>
</div>