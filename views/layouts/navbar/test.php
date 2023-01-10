<div class="container">
    <div class="sidebar">
        <div class="logo">
            <img src="logo.svg" alt="logo" />
        </div>
        <ul class="nav_list">
            <li>
                <a href="index.php" class="nav_link {Request}">
                    <!-- add notes box icon -->
                    <i class='bx bxs-note'></i>
                    <span class="nav_name">Request</span>
                </a>
            </li>

            <li>
                <a href="#" class="nav_link {Events}">
                    <i class='bx bx-calendar-event'></i>
                    <span class="nav_name">Events</span>
                </a>
            </li>

            <li>
                <a href="#" class="nav_link {CCs}">
                    <i class='bx bxs-building-house'></i>
                    <span class="nav_name">Community Centers</span>
                </a>
            </li>

            <li>
                <a href="#" class="nav_link">
                    <i class='bx bx-error-alt'></i>
                    <span class="nav_name">Complaints</span>
                </a>
            </li>
            <li class="logout">
                <a href="/CommuSupport/logout" class="nav_link">
                    <i class='bx bx-log-out'></i>
                    <span class="nav_name">Logout</span>
                </a>
            </li>
        </ul>

    </div>
    <div class="main">
        {content}
    </div>
</div>
