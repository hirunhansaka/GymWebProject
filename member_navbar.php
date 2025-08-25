<nav>
    <div class="logo">FitZone <span>Member</span></div>
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
        <li><a href="member_dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'member_dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>

        <li>
            <form action="./search.php" method="GET" class="nav-search">
                <input type="text" name="q" placeholder="Search...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </li>
        <li><a href="./logout.php" class="btn">Logout</a></li>
    </ul>
</nav>