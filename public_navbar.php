<nav>
    <a href="./index.php" class="logo-link">
        <div class="logo">FitZone</div>
    </a>
    <div class="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>
    <ul class="nav-links">
        <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a></li>
        <li><a href="about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About</a></li>
        <li><a href="services.php" class="<?= basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : '' ?>">Services</a></li>
        <li><a href="timetable.php" class="<?= basename($_SERVER['PHP_SELF']) == 'timetable.php' ? 'active' : '' ?>">Timetable</a></li>
        <li><a href="membership.php" class="<?= basename($_SERVER['PHP_SELF']) == 'membership.php' ? 'active' : '' ?>">Membership</a></li>
        <li><a href="contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
        <li><a href="blog.php" class="<?= basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : '' ?>">Blog</a></li>
        <li>
            <form action="search.php" method="GET" class="nav-search">
                <input type="text" name="q" placeholder="Search...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </li>
        <li><a href="login.php" class="btn">Login</a></li>
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