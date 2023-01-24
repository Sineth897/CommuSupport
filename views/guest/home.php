<link rel="stylesheet" href="/CommuSupport/public/CSS/landingPage/landingpage.css">
<div class="container">
    <section class="navbar">
        <div class="nav_logo">
            <img src="/CommuSupport/public/src/landingpage/CMS-Logo.svg" alt="logo">
        </div>
        <div class="nav_set">
            <ul class="nav-list">
                <li class="nav-link"><a href="#">Home</a></li>
                <li class="nav-link"><a href="#">Community Centers</a></li>
                <li class="nav-link"><a href="#">Events</a></li>
            </ul>
        </div>
        <div class="nav-btns">
            <a href="./login/user">
                <button id="login">
                    Log in
                </button>
            </a>
            <button id="signup"  class="action-btn">
                Create an Account
            </button>
        </div>

    </section>
    <section class="hero">
        <div class="hero-box">
            <h1 id="title"><span class="title-color">CommuSupport<br></span>From the Community. For the Community.</h1>
            <p id="desc">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium architecto consectetur eligendi
                hic,
                illum impedit incidunt iure nam neque nihil placeat, quaerat rem sapiente velit.
            </p>
            <div class="hero-btns">
                <button id="readmore"  class="action-btn">
                    Read More
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
        <img src="/CommuSupport/public/src/landingpage/01.svg" alt="image">
        <div class="text">
            <h3>Opportunity to Request from the Community</h3>
            <p>As some donors will have a hard time transporting their donations. We will pick up the delivery right
                from your doorstep</p>
        </div>
    </div>
    <div class="info-block">
        <div class="text">
            <h3></h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum et eveniet hic id inventore ipsum maiores
                minus nostrum, nulla omnis placeat quaerat quasi quidem reprehenderit unde velit voluptate. Accusamus
                delectus dolor ex impedit non, nulla sequi totam. Deleniti eius illum minima mollitia, necessitatibus
                nihil porro quasi quidem similique vero vitae?</p>
        </div>
        <img src="/CommuSupport/public/src/landingpage/02.svg" alt="image">
    </div>

    <div class="info-block">
        <img src="/CommuSupport/public/src/landingpage/03.svg" alt="image">
        <div class="text">
            <h3>Getting donations from drivers to drivers</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum et eveniet hic id inventore ipsum maiores
                minus nostrum, nulla omnis placeat quaerat quasi quidem reprehenderit unde velit voluptate. Accusamus
                delectus dolor ex impedit non, nulla sequi totam. Deleniti eius illum minima mollitia, necessitatibus
                nihil porro quasi quidem similique vero vitae?</p>
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


