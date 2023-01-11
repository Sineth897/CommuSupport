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
        <div class="nav-btns">
            <button id="login">
                Log in
            </button>
            <button id="signup">
                Create an Account
            </button>
        </div>
    </div>
</section>
<section class="hero">
    <div class="hero-text">
        <h1 id="title">Helping Hand to <span class="title-color">Helping Hand</span></h1>
        <p id="desc">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium architecto consectetur eligendi hic,
            illum impedit incidunt iure nam neque nihil placeat, quaerat rem sapiente velit.
        </p>
        <div class="hero-btns">
            <button id="readmore">
                Read More
            </button>
        </div>
    </div>
    <div class="hero-image">
        <img src="/CommuSupport/public/src/landingpage/hero-image.svg" alt="Image">
    </div>
</section>

<section class="counter">
    <div class="counter-grid">
        <div class="counter-item">
            <div class="counter-item-content"><h1 class="number">69+</h1><h6 class="label">Projects</h6></div>
        </div>
        <div class="counter-item">
            <div class="counter-item-content"><h1 class="number">69+</h1><h6 class="label">Projects</h6></div>
        </div>
        <div class="counter-item">
            <div class="counter-item-content"><h1 class="number">69+</h1><h6 class="label">Projects</h6></div>
        </div>
    </div>
</section>
<section class="info">
    <h1 id="info-title">
        How we work
    </h1>
    <div class="info-block">
        <img src="" alt="image">
        <div class="text">
            <h3>Getting donations from drivers to drivers</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum et eveniet hic id inventore ipsum maiores
                minus nostrum, nulla omnis placeat quaerat quasi quidem reprehenderit unde velit voluptate. Accusamus
                delectus dolor ex impedit non, nulla sequi totam. Deleniti eius illum minima mollitia, necessitatibus
                nihil porro quasi quidem similique vero vitae?</p>
        </div>
    </div>
    <div class="info-block">
        <div class="text">
            <h3>Getting donations from drivers to drivers</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum et eveniet hic id inventore ipsum maiores
                minus nostrum, nulla omnis placeat quaerat quasi quidem reprehenderit unde velit voluptate. Accusamus
                delectus dolor ex impedit non, nulla sequi totam. Deleniti eius illum minima mollitia, necessitatibus
                nihil porro quasi quidem similique vero vitae?</p>
        </div>
        <img src="" alt="image">

    </div>
    <div class="info-block">
        <img src="" alt="image">
        <div class="text">
            <h3>Getting donations from drivers to drivers</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum et eveniet hic id inventore ipsum maiores
                minus nostrum, nulla omnis placeat quaerat quasi quidem reprehenderit unde velit voluptate. Accusamus
                delectus dolor ex impedit non, nulla sequi totam. Deleniti eius illum minima mollitia, necessitatibus
                nihil porro quasi quidem similique vero vitae?</p>
        </div>
    </div>
</section>
<section class="contactus">
    <img src="" alt="">
    <form class="contact-form">
        <input type="text" name="name" id="name">
        <input type="email" name="email" id="email">
        <textarea name="message" id="message" cols="30" rows="10"></textarea>
        <button type="submit">Contact Us</button>
    </form>
</section>
<section class="footer">
    <p>2023 Copyright of Commu-Support</p>
</section>


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


