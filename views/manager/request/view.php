<?php


?>


<!--profile div-->
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


<?php
$headerDiv = new \app\core\components\layout\headerDiv();

$headerDiv->heading("Requests");

$headerDiv->pages(["pending","published","history"]);

$headerDiv->end();
?>

<!--        Search and filter boxes -->
<?php
$searchDiv = new \app\core\components\layout\searchDiv();

$searchDiv->filters();

$searchDiv->end();
?>

<div class="filler main">

</div>
