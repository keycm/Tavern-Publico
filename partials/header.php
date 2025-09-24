<header class="main-header">
    <div class="header-content">
        <div class="logo">
            <div class="logo-main-line">
                <span>Tavern Publico</span>
            </div>
            <span class="est-year">EST ★ 2024</span>
        </div>

        <nav class="main-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="events.php">Events</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>

        <div class="header-right">
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                // User is logged in
                echo '<div class="user-profile-menu">';
                echo '<div class="notification-dropdown">';
                echo '<button class="notification-button">';
                echo '<span class="notification-bell"><i class="fas fa-bell"></i></span>';
                echo '<span class="notification-badge" id="notificationCount" style="display: none;">0</span>';
                echo '</button>';
                echo '<div class="notification-dropdown-content" id="notificationDropdownContent">';
                echo '<p class="no-notifications">No new notifications.</p>';
                echo '</div>';
                echo '</div>';
                echo '<a href="logout.php" class="btn header-button logout-button">Logout</a>';
                echo '</div>';
            } else {
                // User is not logged in
                echo '<a href="#" class="btn header-button signin-button" id="openModalBtn">Sign In/Sign Up</a>';
            }
            ?>
        </div>

        <button class="mobile-nav-toggle" aria-label="Open navigation menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>