<?php
/** @var $model \app\models\doneeModel */
/**  @userID string */

$doneeID = $_GET['doneeID'];
?>

<link rel="stylesheet" href="/CommuSupport/public/CSS/individual/donee.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/form/form.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/notification/notification.css">


<div class="profile-container">
    <div class="stat-card-container">
        <div class="stat-card">
            <span class="stat-title">Total Requests Posted</span>
            <span class="stat-value-individual">100</span>
            <span class="stat-movement"><i class="material-icons">inventory_2</i></span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Total Donations Recieved</span>
            <span class="stat-value-individual">100</span>
            <span class="stat-movement"><i class="material-icons">local_shipping</i></span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Total Events Participated</span>
            <span class="stat-value-individual">100</span>
            <span class="stat-movement"><i class="material-icons">emoji_events</i></span>
        </div>
    </div>
    <div class="profile-block">
        <?php
        $retrievedData = $model->getDoneePersonalInfo($doneeID);
        //        var_dump($personalInfo);
        //        $personalInfo =  $model->getDoneePersonalInfo();
        $personalInfo = $retrievedData[0];
//        var_dump($personalInfo);
        ?>
        <div class="img-username-div">

            <div class="profile-img">
            </div>
            <h2><?php echo $personalInfo['doneeName'] ?></h2>
        </div>
        <div class="form-grid-2-2">

            <div class="form-group">
                <label class="form-label"> Name </label>
                <input class="basic-input-field" value="<?php echo $personalInfo['doneeName'] ?>" disabled>
            </div>

            <div class="form-group">
                <label for="" class="form-label">Community Center</label>
                <input type="text" class="basic-input-field" value="<?php echo $personalInfo['communityCenterName']; ?>" disabled>
            </div>

            <?php
            if ($personalInfo['type'] === 'Organization') {
                ?>
                <div class="form-group">
                    <label class="form-label"> Representative </label>
                    <input class="basic-input-field" value="<?php echo $personalInfo['representative'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="" class="form-label">Representive Contact</label>
                    <input type="text" class="basic-input-field"
                           value="<?php echo $personalInfo['representativeContact']; ?>" disabled>
                </div>
            <?php } ?>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input class="basic-input-field" value="<?php echo $personalInfo['contactNumber'] ?>" disabled>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Email Address</label>
                <input type="text" class="basic-input-field"
                       value="<?php echo $personalInfo['email']; ?>" disabled>
            </div>
        </div>

    </div>
    <div class="user-activity">
        <p>
            User Activity
        </p>
        <div class="activity-scroller">

                <?php

                $notificationIcon = [
                    'event' => 'event',
                    'directDonations' => 'local_shipping',
                    'request' => 'local_shipping',
                    'acceptedRequests' => 'local_shipping',
                    'delivery' => 'local_shipping',
                    'ccDonation' => 'local_shipping',
                    'complaint' => 'report',
                ];

                $notifications = \app\models\notificationModel::getNotification(['userID' => $doneeID, 'usertype' =>  'donee']);

                if (empty($notifications)) {
                    echo "<h2 class='no-notification'> No notifications to show</h2>";
                }

                foreach ($notifications as $notification) {

                    echo "<div class='profile-notif-card'>";
                    echo "<div class='profile-notif-left-block'>";

                    // title and the message
                    echo "<div class='profile-notif-message'>";
                    echo sprintf("<h4> %s </h4>",$notification['title']);
                    echo sprintf("<p><small>%s</small></p>",$notification['message']);
                    echo "</div>";

                    // date and time
                    echo "<div class='profile-notif-date-time'>";
                    echo sprintf("<p class='date'>%s</p>",date('M d',strtotime($notification['dateCreated'])));
                    echo sprintf("<p class='time'>%s</p>",date('g:i a',strtotime($notification['dateCreated'])));
                    echo "</div>";

                    echo "</div>";

                    echo "<div class='profile-notif-right-block'>";

                    // icon for related process
                    echo    sprintf("<i class='material-icons'>%s</i>", $notificationIcon[$notification['related']]);

                    echo "</div>";

                    echo "</div>";

                }

                ?>
        </div>
    </div>
</div>