<link rel="stylesheet" href="../../public/CSS/button/button-styles.css">
<?php
/** @var $cho \app\models\choModel */
/** @var $user \app\models\userModel */
?>

<?php
echo '<pre>';
if(empty(\app\core\Application::session()->getFlash('success'))) {
    print_r(\app\core\Application::session()->getFlash('error'));
} else if(empty(\app\core\Application::session()->getFlash('error'))) {
    print_r(\app\core\Application::session()->getFlash('success'));
}
echo '</pre>';
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
        <h1>Create a Community Head Office</h1>
    </div>
</div>

<div class="content">

    <?php $choRegistrationForm = \app\core\components\form\form::begin('','post') ?>

    <?php $choRegistrationForm->dropDownList($cho, "District",'district',$cho->getDistricts()) ?>

    <?php $choRegistrationForm->inputField($cho,"Contact Number",'text','contactNumber') ?>

    <?php $choRegistrationForm->inputField($cho,"Email",'text','email') ?>

    <?php $choRegistrationForm->inputField($cho,"Address",'text','address') ?>

    <?php $choRegistrationForm->inputField($user, "Username",'text','username') ?>

    <?php $choRegistrationForm->inputField($user, "Password",'password','password') ?>

        <div>
            <?php $choRegistrationForm->button("Confirm") ?>
        </div>


    <?php $choRegistrationForm->end() ?>
</div>



