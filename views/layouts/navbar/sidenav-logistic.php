<body>
<link rel="stylesheet" href="/CommuSupport/public/CSS/navbar/sidenav-styles.css">
<link rel="stylesheet" href="/CommuSupport/public/CSS/navbar/main-styles.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="sidenav">
    <div class="logo_content">
        <div class="logo">
            <img src="/CommuSupport/public/src/navlogo/CMS_Logistics.svg" alt="" class="logo_name">
        </div>
        <i class="material-icons" id="btn">menu</i>
    </div>
    <ul class="nav_list">
        <li>
            <a href="/CommuSupport/logisitic/donations">
                <i class="material-icons">inventory_2</i>
                <span class="links_name">Donations</span>
            </a>
            <span class="tooltip">Donations</span>
        </li>
        <li>
            <a href="/CommuSupport/logisitic/deliveries">
                <i class="material-icons">local_shipping</i>
                <span class="links_name">Deliveries</span>
            </a>
            <span class="tooltip">Deliveries</span>
        </li>
        <li>
            <a href="/CommuSupport/logisitic/inventory">
                <i class="material-icons">inventory</i>
                <span class="links_name">Inventory</span>
            </a>
            <span class="tooltip">Inventory</span>
        </li>
        <li>
            <a href="/CommuSupport/logisitic/requests">
                <i class="material-icons">summarize</i>
                <span class="links_name">Requests</span>
            </a>
            <span class="tooltip">Requests</span>
        </li>
        <li>
            <a href="/CommuSupport/logisitic/drivers">
                <i class="material-icons">local_shipping</i>
                <span class="links_name">Drivers</span>
            </a>
            <span class="tooltip">Drivers</span>
        </li>
    </ul>
    <div class="logout">
        <form method="post" action="/CommuSupport/logout"><button><i class="material-icons" id="log_out">power_settings_new</i></button></form>
    </div>
</div>
<div class="home_content">
    {content}
</div>

<script src="/CommuSupport/public/JS/navbar/sidebar-scripts.js"></script>
</body>
</html>

