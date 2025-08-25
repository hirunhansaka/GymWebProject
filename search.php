<?php
require_once 'auth_functions.php';
require_once 'config.php';

$is_logged_in = isLoggedIn();
$user_type = $is_logged_in ? $_SESSION['user_type'] : null;

$search_results = [];
$search_query = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['q'])) {
    $search_query = trim($_GET['q']);

    if (!empty($search_query)) {

        $stmt = $conn->prepare("
            SELECT page_url, page_title, 
                   MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE) AS score
            FROM search_index 
            WHERE MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)
            ORDER BY score DESC
        ");
        $stmt->bind_param("ss", $search_query, $search_query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $search_results = $result->fetch_all(MYSQLI_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - FitZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .search-container {
            max-width: 1200px;
            margin: 100px auto 50px;
            padding: 20px;
        }

        .search-box {
            display: flex;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .search-box input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #E63946;
            border-right: none;
            font-size: 1.1rem;
            outline: none;
        }

        .search-box button {
            padding: 0 25px;
            background: #E63946;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .search-results {
            margin-top: 30px;
        }

        .result-item {
            margin-bottom: 25px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .result-item h3 {
            margin-top: 0;
            color: #E63946;
        }

        .result-item a {
            color: #E63946;
            text-decoration: none;
            font-weight: 600;
        }

        .result-item a:hover {
            text-decoration: underline;
        }

        .no-results {
            text-align: center;
            padding: 50px;
            font-size: 1.2rem;
            color: #666;
        }

        .highlight {
            background-color: #FFF3B0;
            padding: 0 2px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header>
        <?php if ($is_logged_in) {
            switch ($user_type) {
                case 'admin':
                    require_once 'admin_navbar.php';
                    break;
                case 'manager':
                    require_once 'manager_navbar.php';
                    break;
                case 'member':
                    require_once 'member_navbar.php';
                    break;
            }
        } else {
            require_once 'public_navbar.php';
        } ?>
    </header>

    <main class="search-container">
        <h1>Search Results</h1>

        <form method="GET" action="search.php" class="search-box">
            <input type="text" name="q" placeholder="Search FitZone..." value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>

        <div class="search-results">
            <?php if (!empty($search_query)): ?>
                <?php if (!empty($search_results)): ?>
                    <?php foreach ($search_results as $result): ?>
                        <div class="result-item">
                            <h3><a href="<?= htmlspecialchars($result['page_url']) ?>"><?= htmlspecialchars($result['page_title']) ?></a></h3>
                            <p><?= str_ireplace($search_query, '<span class="highlight">' . $search_query . '</span>', substr($result['content'], 0, 200)) ?>...</p>
                            <a href="<?= htmlspecialchars($result['page_url']) ?>">Read more</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-results">
                        <p>No results found for "<?= htmlspecialchars($search_query) ?>"</p>
                        <p>Try different keywords or check out our <a href="services.php">services</a>.</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>Enter a search term to find what you're looking for.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <span>Fit</span>Zone
                </div>
                <div class="footer-links">
                    <a href="about.php">About Us</a>
                    <a href="services.php">Services</a>
                    <a href="timetable.php">Timetable</a>
                    <a href="contact.php">Contact</a>
                </div>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="copyright">
                &copy; 2025 FitZone. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>

</html>