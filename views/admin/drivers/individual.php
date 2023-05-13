<?php
/** @var $model \app\models\driverModel */
/**  @employeeID string */
?>
<link rel="stylesheet" href="/CommuSupport/public/CSS/individual/driver.css">
<?php
$array = $model->getDriverStatisticsForAdmin($employeeID);

$completed = $array[0]["completed"];
$assigned = $array[0]["assigned"];
$lessThan10 = $array[0]["lessThan10"] === null ? 0 : $array[0]["lessThan10"];
$greaterThan10 = $array[0]["greaterThan10"] === null ? 0 : $array[0]["greaterThan10"];

?>

<div class="profile-container">
    <div class="stat-card stat1">
        <span class="stat-title">Completed Deliveries</span>
        <span class="stat-value"><?php echo $completed ?>
<!--</span>-->
<!--        <span class="stat-icon"><i class="material-icons">done</i></span>-->
    </div>
    <div class="stat-card stat2">
        <span class="stat-title">Currently Assigned Deliveries</span>
        <span class="stat-value"><?php echo $assigned ?></span>
<!--        <span class="stat-icon"><i class="material-icons">cached</i></span>-->
    </div>
    <div class="stat-card stat3">
        <span class="stat-title">Short Distance</span>
        <span class="stat-value"><?php echo $lessThan10 ?></span>
        <span class="stat-movement dec">
    </div>
    <div class="stat-card stat4">

        <span class="stat-title">Long Distance</span>
        <span class="stat-value"><?php echo $greaterThan10 ?></span>
        <span class="stat-movement dec">
    </div>
    <div class="profile-block">
        <div class="img-div">
            <div class="img"></div>
        </div>
        <div class="details">
            <form action="#" class="form-grid-2-2">

                <div class="form-group">
                    <label class="form-label"> Name </label>
                    <input class="basic-input-field" value="" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label"> Contact Number </label>
                    <input class="basic-input-field" value="" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label"> Vehicle Number </label>
                    <input class="basic-input-field" value="" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label"> Vehicle Type </label>
                    <input class="basic-input-field" value="" disabled>
                </div>


                <div class="form-group">
                    <label class="form-label"> Preference </label>
                    <input class="basic-input-field" value="" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label"> Community Center </label>
                    <input class="basic-input-field" value="" disabled>
                </div>

            </form>
        </div>
        <div class="btn-div">
            <button class="btn-small-primary">Edit Details</button>
        </div>
    </div>
    <div class="user-activity">
        <p>All Activity</p>
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
