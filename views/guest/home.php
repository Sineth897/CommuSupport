<link rel="stylesheet" href="./public/CSS/landingPage/landingpage.css">
<link rel="stylesheet" href="./public/CSS/button/button-styles.css">
<link rel="stylesheet" href="./public/CSS/popup/popup-styles.css">
<link rel="stylesheet" href="./public/CSS/form/form.css">
<!--<link rel="stylesheet" href="./public/CSS/registration/all-reg.css">-->
<link rel="stylesheet" href="./public/CSS/registration/reg-base.css">



<div class="container">
    <section class="navbar">
        <div class="nav_logo">
            <img src="./public/src/landingpage/CMS-Logo.svg" alt="logo">
        </div>
        <div class="nav-btns">
            <a href="./login/user">
                <button id="login">
                    Log in
                </button>
            </a>
            <button id="signup" class="action-btn">
                Create an Account
            </button>
        </div>

    </section>
    <section class="hero">
        <div class="hero-box">
            <h1 id="title"><span class="title-color">CommuSupport<br></span>From the Community. For the Community.</h1>
            <p id="desc">
                Our goal is to bring people together and create a strong, supportive network where everyone can make a
                difference.
                <br>
                <br>
                Join us in making a difference. Be a part of the CommuSupport community and show your support today.
                Together, we can create a brighter tomorrow for all.
            </p>
            <div class="hero-btns">
                <button id="readmore" class="action-btn">
                    Join with Us!
                </button>
            </div>
        </div>
    </section>
</div>
<section class="counter">
    <div class="counter-grid">
        <div class="counter-item">
            <div class="counter-item-content"><h1 class="number">1000+</h1><h6 class="label">BRANCHES ISLANDWIDE</h6>
            </div>
        </div>
        <div class="counter-item">
            <div class="counter-item-content"><h1 class="number">1M+</h1><h6 class="label">ACTIVE USERS</h6></div>
        </div>
        <div class="counter-item">
            <div class="counter-item-content"><h1 class="number">100K</h1><h6 class="label">COMPLETED DONATIONS</h6>
            </div>
        </div>
    </div>
</section>
<section class="info">
    <h1 id="info-title">
        How we work
    </h1>
    <div class="info-block">
        <img src="./public/src/landingpage/01.svg" alt="image">
        <div class="text">
            <h3>Opportunity to Request from the Community</h3>
            <p>As some donors will have a hard time transporting their donations. We will pick up the delivery right
                from your doorstep</p>
        </div>
    </div>
    <div class="info-block">
        <div class="text">
            <h3>Delivery from anywhere to anywhere</h3>
            <p>Drivers from our community centers will handle the delivery from both parties (Donor and Donee)</p>
        </div>
        <img src="./public/src/landingpage/02.svg" alt="image">
    </div>

    <div class="info-block">
        <img src="./public/src/landingpage/03.svg" alt="image">
        <div class="text">
            <h3>Maximum security and privacy for everyone.</h3>
            <p>Privacy and security of all users will be protected and the donation process will be handled with every step recorded to avoid issues.</p>
        </div>
    </div>

</section>

<section class="footer">
    <div class="grid-2-1-1">
        <div class="contact">
            <h3>CONTACT</h3>
            <form action="#" class="contact-form">
                <input type="text" name="name" id="name" placeholder="Name" class="basic-input-field">
                <input type="email" name="email" id="email" placeholder="Email" class="basic-input-field">
                <button type="submit" class="contact-btn">Contact Us</button>
            </form>
        </div>
        <div class="link-list one">
            <h4>
                LEARN
            </h4>
            <ul>
                <li>NGO</li>
                <li>Community Centers</li>
                <li>Officers</li>
                <li>Events</li>
            </ul>
        </div>
        <div class="link-list two">
            <h4>
                SERVICES
            </h4>
            <ul>
                <li>Delivery</li>
                <li>Inventory</li>
                <li>Tracking</li>
                <li>Public Service</li>
            </ul>

        </div>

    </div>
    <p class="copyright">Copyright 2023 CommuSupport</p>
</section>


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
                <a href="./register/donor">
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
                <a href="./register/donee">
                    <button>Register as a Donee</button>
                </a>
            </div>
<div class="close" id="signupCloseBtn">
    <i class="material-icons">close</i>
</div>
        </div>



<script type="module" src="./public/JS/guest/home.js"></script>

<!--
<?php $userLogin = \app\core\components\form\form::begin('./login/user', 'get'); ?>

<button> User Login</button>

<?php $userLogin->end(); ?>

<?php $userLogin = \app\core\components\form\form::begin('./login/employee', 'get'); ?>

<button> Employee Login</button>

<?php $userLogin->end(); ?>




<?php
if (\app\core\Application::session()->getFlash('success')) {
    echo \app\core\Application::session()->getFlash('success');
    var_dump($_SESSION);
}
?>

-->


