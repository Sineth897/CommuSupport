
<?php



?>

<div class="form-grid-1">

    <div id="usernameDiv" class="form-group">
        <label for="username" class="form-label">Username:</label>
        <input id="username" type="text" size="40" class="basic-input-field">
        <span id="usernameError" class="error"></span>
        <button id="usernameSubmit" class="btn-primary">Confirm</button>
    </div>

    <div id="otpDiv" class="form-group" style="display: none">
        <label for="otp" class="form-label">Enter your otp:</label>
        <input id="otp" type="password" size="40" class="basic-input-field">
        <span id="otpError" class="error"></span>
        <button id="otpSubmit" class="btn-primary" style="display: none">Submit</button>
        <button id="otpRequest" class="btn-primary">Request OTP</button>
    </div>

</div>






<script type="module" src="/CommuSupport/public/JS/login/forgetPassword/forgetPassword.js"></script>