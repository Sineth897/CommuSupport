<body>
<link rel="stylesheet" href="/CommuSupport/public/CSS/navbar/sidenav-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/navbar/main-styles.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="sidenav">
    <div class="logo_content">
        <div class="logo">
            <img src="/CommuSupport/public/src/navlogo/CMS_Donee.svg" alt="" class="logo_name">
        </div>
        <i class="material-icons" id="btn">menu</i>
    </div>
    <ul class="nav_list">
        <li>
            <a href="/CommuSupport/donee/request">
                <i class="material-icons">summarize</i>
                <span class="links_name">Requests</span>
            </a>
<!--            <span class="tooltip">Requests</span>-->
        </li>
        <li>
            <a href="/CommuSupport/donee/events">
                <i class="material-icons">today</i>
                <span class="links_name">Events</span>
            </a>
<!--            <span class="tooltip">Events</span>-->
        </li>
        <li>
            <a href="/CommuSupport/donee/communitycenters">
                <i class="material-icons">emoji_transportation</i>
                <span class="links_name">Community Center</span>
            </a>
<!--            <span class="tooltip">Community Center</span>-->
        </li>
        <li>
            <a href="/CommuSupport/donee/complaints">
                <i class="material-icons">report_gmailerrorred</i>
                <span class="links_name">Complaints</span>
            </a>
<!--            <span class="tooltip">Complaints</span>-->
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
</html>

