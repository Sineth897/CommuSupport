<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<?php
 /** @var $user \app\models\userModel */

?>


<?php

$mobileVerificationForm = \app\core\components\form\form::begin('','');

$mobileVerificationForm->button('Request OTP','button','requestBtn');
?>
<span id="otpCountDown" class="success"></span>

<div id="otpInputDiv">
    <?php
    $mobileVerificationForm->inputField($user,'Enter OTP','text','','otpInput');

    echo "<span id='otpError' class='error'></span>";

    $mobileVerificationForm->button('Submit','button','submitBtn');
    ?>
</div>


<?php
$mobileVerificationForm->end();
?>

<script type="module" src="./public/JS/mobileVerification/index.js"></script>
