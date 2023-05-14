<body>
<link rel="stylesheet" href="/CommuSupport/public/CSS/navbar/sidenav-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/navbar/main-styles.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <div class="sidenav">
        <div class="logo_content">
            <div class="logo">
                <img src="/CommuSupport/public/src/navlogo/CMS_Manager.svg" alt="" class="logo_name">
            </div>
<!--            <i class="material-icons" id="btn">menu</i>-->
        </div>
        <ul class="nav_list">
            <li>
                <a href="/CommuSupport/manager/requests">
                    <i class="material-icons">summarize</i>
                    <span class="links_name">Requests</span>
                </a>
<!--                <span class="tooltip">Requests</span>-->
            </li>
            <li>
                <a href="/CommuSupport/manager/events">
                    <i class="material-icons">today</i>
                    <span class="links_name">Events</span>
                </a>
<!--                <span class="tooltip">Events</span>-->
            </li>
            <li>
                <a href="/CommuSupport/manager/donations">
                    <i class="material-icons">inventory_2</i>
                    <span class="links_name">Donations</span>
                </a>
<!--                <span class="tooltip">Donations</span>-->
            </li>
            <li>
                <a href="/CommuSupport/manager/donees">
                    <i class="material-icons">emoji_people</i>
                    <span class="links_name">Donees</span>
                </a>
<!--                <span class="tooltip">Donees</span>-->
            </li>
            <li>
                <a href="/CommuSupport/manager/donors">
                    <i class="material-icons">accessibility_new</i>
                    <span class="links_name">Donors</span>
                </a>
<!--                <span class="tooltip">Donors</span>-->
            </li>
            <li>
                <a href="/CommuSupport/manager/drivers">
                    <i class="material-icons">local_shipping</i>
                    <span class="links_name">Drivers</span>
                </a>
<!--                <span class="tooltip">Drivers</span>-->
            </li>

        </ul>
    <div class="logout">
        <form method="post" action="/CommuSupport/logout"><button><i class="material-icons" id="log_out">power_settings_new</i></button></form>
    </div>
    </div>
    <div class="main">
        {content}
    </div>
<script src="/CommuSupport/public/JS/navbar/sidebar-scripts.js"></script>
</body>