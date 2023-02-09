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
                        <h1>Employee Login</h1>
                        <p>Enter the information provided by relevant superior officer!</p>
                    </div>

                    <label for="username">Username</label>
                    <input type="text" name="username" id="username">
                    <?php echo sprintf('<span class="error">%s</span>', $user->getFirstError('username')) ?>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                    <?php echo sprintf('<span class="error">%s</span>', $user->getFirstError('password')) ?>

                    <div class="login-footer">
                        <div class="remember-me">
                            <input type="checkbox" name="rememberMe" id="rememberMe">
                            <label for="rememberMe">Remember me</label>
                        </div>
                        <a href="/CommuSupport/forgetpassword">Forgot Password?</a>
                    </div>

                    <div class="btn-block">
                        <button>Login</button>
                    </div>

                    <div class="register">
                        <p>Not registered yet?</p>
                        <p>Contact relevant superior officer for registration</p>
                    </div>

                </form>
            </div>
        </div>
        <div class="right-block">
            <div class="logo-block">
                <img src="/CommuSupport/public/src/login/CMSlogo.svg" alt="Logo">
            </div>
            <div class="emp-text">
                <a href="/CommuSupport/login/user">
                <button class="emp-btn"><span class="material-icons">keyboard_backspace</span>Back to User Login</button>
                </a>
            </div>
            <img src="/CommuSupport/public/src/login/emp-login.svg" alt="donation">
        </div>

    </div>
</div>
</body>
