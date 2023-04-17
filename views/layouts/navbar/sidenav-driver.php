<body>
<link rel="stylesheet" href="/CommuSupport/public/CSS/navbar/sidenav-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/navbar/main-styles.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="sidenav">
    <div class="logo_content">
        <div class="logo">
            <img src="/CommuSupport/public/src/navlogo/CMS_Driver.svg" alt="" class="logo_name">
        </div>
        <i class="material-icons" id="btn">menu</i>
    </div>
    <ul class="nav_list">
         <li>
            <a href="/CommuSupport/driver/deliveries">
                <i class="material-icons">local_shipping</i>
                <span class="links_name">Deliveries</span>
            </a>
<!--            <span class="tooltip">Deliveries</span>-->
        </li>
        <li>
            <a href="/CommuSupport/driver/deliveries/completed">
                <i class="material-icons">task_alt</i>
                <span class="links_name">Completed</span>
            </a>
<!--            <span class="tooltip">Completed</span>-->
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

