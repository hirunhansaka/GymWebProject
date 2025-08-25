<nav>
    <a href="./index.php" class="logo-link">
        <div class="logo">FitZone <span>Manager</span></div>
    </a>
    <div class="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>
    <ul class="nav-links">
        <li><a href="./index.php">Home</a></li>
        <li><a href="./about.php">About</a></li>
        <li><a href="./services.php">Services</a></li>
        <li><a href="./timetable.php">Timetable</a></li>
        <li><a href="./membership.php">Membership</a></li>
        <li><a href="./contact.php">Contact</a></li>
        <li><a href="./blog.php">Blog</a></li>

        <li><a href="manager_dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manager_dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>

        <li>
            <form action="./search.php" method="GET" class="nav-search">
                <input type="text" name="q" placeholder="Search...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </li>
        <li><a href="./logout.php" class="btn">Logout</a></li>
    </ul>
</nav>


<style>
    .logo-link {
        text-decoration: none;
        color: inherit;
    }

    .logo-link:hover {
        text-decoration: none;
    }
</style>