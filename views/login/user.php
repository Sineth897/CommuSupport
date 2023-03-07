<?php

/** @var $user \app\models\userModel*/

?>

<link rel="stylesheet" href="../public/CSS/popup/popup-styles.css">
<link rel='stylesheet' href='../public/CSS/login/login-page.css'>
<link rel='stylesheet' href='../public/CSS/registration/reg-base.css'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="background">
    <div class="grid-2">
        <div class="left-block">
            <div class="login-form">
                <form action="" method="post">

                    <div class="form-head">
                        <h1>User Login</h1>
                        <p>Enter the information you entered while registering.</p>
                    </div>

                    <label for="username">Username</label>
                    <input type="text" name="username" id="username">
                    <?php echo sprintf('<span class="error">%s</span>', $user->getFirstError('username')) ?>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                    <?php echo sprintf('<span class="error">%s</span>', $user->getFirstError('password')) ?>

                    <div class="login-footer">
                        <div class="remember-me">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="/CommuSupport/forgetpassword">Forgot Password?</a>
                    </div>

                    <div class="btn-block">
                        <button>Login</button>
                    </div>

                    <div class="register">
                        <p>Not registered yet?
                        <a href="#" id="signup">Create an Account</a></p>
                    </div>

                </form>
            </div>
        </div>
        <div class="right-block">
            <div class="logo-block">
                <img src="/CommuSupport/public/src/login/CMSlogo.svg" alt="Logo">
            </div>
            <div class="emp-text">
                <a href="/CommuSupport/login/employee">
                    <button class="emp-btn"><span class="material-icons">manage_accounts</span>Employee Login</button>
                </a>
                <!--                <p>-->
                <!--                    Are you an Employee? <a href="#">Login</a></p>-->
            </div>
            <img src="/CommuSupport/public/src/login/user-login.svg" alt="donation">
        </div>

    </div>

    <div id="signupPopup" class="popup-background">
        <div class="popup login-grid-2">
            <div class="block left">
                <div class="block-header">
                    <h1>Donor</h1>
                    <p>Join the community to help each other</p>
                </div>
                <ul>
                    <li><i class="material-icons">volunteer_activism</i>View and accept requests.</li>
                    <li><i class="material-icons">local_shipping</i>Create direct donations</li>
                    <li><i class="material-icons">verified_user</i>Guaranteed security for the donations</li>
                    <li><i class="material-icons">calendar_today</i>View and participate in charity events.</li>
                    <li><i class="material-icons">lock</i>Complete anonymity</li>
                </ul>
                <a href="../register/donor">
                    <button>Register as a Donor</button>
                </a>
            </div>

            <div class="block right">
                <div class="block-header">
                    <h1>Donee</h1>
                    <p>Request help from the community</p>
                </div>
                <ul>
                    <li><i class="material-icons">volunteer_activism</i>Ask for Public Requests.</li>
                    <li><i class="material-icons">local_shipping</i>Track Order Deliveries</li>
                    <li><i class="material-icons">verified_user</i>Guaranteed security throughout the process</li>
                    <li><i class="material-icons">calendar_today</i>View and participate in charity events.</li>
                    <li><i class="material-icons">lock</i>Complete anonymity</li>
                </ul>
                <a href="../register/donee">
                    <button>Register as a Donee</button>
                </a>
            </div>
            <div class="close" id="signupCloseBtn">
                <i class="material-icons">close</i>
            </div>
        </div>
</div>




<script>
    let signup= document.getElementById('signup');
    let signupPopup = document.getElementById('signupPopup');
    let signupCloseBtn = document.getElementById('signupCloseBtn');

    signup.addEventListener('click', function() {
        signupPopup.style.display = 'flex';
    });

    signupCloseBtn.addEventListener('click', function() {
        signupPopup.style.display = 'none';
    });
</script>
