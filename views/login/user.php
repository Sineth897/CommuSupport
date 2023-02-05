<?php

/** @var $user \app\models\userModel*/

?>
<body>
<link rel="stylesheet" href="/CommuSupport/public/CSS/login/login-page.css">
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
                        <a href="#">Forgot Password?</a>
                    </div>

                    <div class="btn-block">
                        <button>Login</button>
                    </div>

                    <div class="register">
                        <p>Not registered yet?
                        <a href="#">Create an Account</a></p>
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
</div>
</body>
