<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/login/forget-password.css">

<?php
 /** @var $user \app\models\userModel */

?>

<div class="background">

    <div class="grid-background">

        <h3>Verify Your Mobile Number</h3>
        <p> Request an OTP for provided mobile number at your registration.
            Enter the OTP received and verify your identity</p>


        <div class="form-group">

            <div class="forget-btns">
                <button id="requestBtn" class="btn-primary">Request OTP</button>
            </div>

            <span id="otpCountDown" class="success" style="font-size: .9rem"></span>

        </div>

        <div id="otpInputDiv">

            <div class="form-group">
                <label for="otpInput" class="form-label">Enter your otp:</label>
                <input id="otpInput" type="text" size="40" class="basic-input-field">
                <span id="otpError" class="error"></span>
                <div class="forget-btns">
                    <button id="submitBtn" class="btn-primary">Submit</button>
                </div>
            </div>

        </div>

        <div class="logout-icon-div">

            <div>
                <i class="material-icons">arrow_back</i>
                <p>  <a href="/CommuSupport/">Back to the home page </a> </p>
            </div>

            <div>
                <form action="/CommuSupport/logout" method="post">
                    <i class="material-icons">logout</i>
                    <input value="Logout" type="submit">
                </form>
            </div>

        </div>

    </div>

</div>

<script type="module" src="./public/JS/mobileVerification/index.js"></script>
