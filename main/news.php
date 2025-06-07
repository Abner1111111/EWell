<?php
require_once('../config/database.php');

// Pagination settings
$articles_per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $articles_per_page;

// Get total number of active articles
$total_stmt = $pdo->query("SELECT COUNT(*) FROM news_articles WHERE status = 'Active'");
$total_articles = $total_stmt->fetchColumn();
$total_pages = ceil($total_articles / $articles_per_page);

// Fetch articles for current page
$stmt = $pdo->prepare("SELECT * FROM news_articles WHERE status = 'Active' ORDER BY publication_date DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $articles_per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All News - Wellness Portal</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0px;
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-left: 0;
            padding-left: 0;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo-img {
            height: 60px;
            width: auto;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        .news-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .news-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .news-card:hover {
            transform: translateY(-5px);
        }

        .news-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .news-content {
            padding: 20px;
        }

        .news-date {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .news-content h3 {
            color: #333;
            margin: 0 0 10px 0;
            font-size: 1.4em;
        }

        .news-content p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .read-more {
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            transition: color 0.3s ease;
        }

        .read-more:hover {
            color: #388E3C;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }

        .pagination a, .pagination span {
            padding: 8px 16px;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .pagination .active {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }

        .pagination .disabled {
            color: #999;
            pointer-events: none;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .section-header p {
            color: #666;
            font-size: 1.1em;
        }

        @media (max-width: 768px) {
            .news-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo-container">
                <img src="../main/image/DTI_w12.png" alt="DTI Logo" class="logo-img">
                <a href="index.php" class="logo">E<span>well</span></a>
            </div>
            <button class="menu-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="index.php">Home</a></li>
                <li><a href="#" class="active">News</a></li>
                <li><a href="index.php#feedback">Feedbacks</a></li>
            </ul>
            <div class="search-container">
                <div class="search-bar" id="searchBar">
                    <input type="text" placeholder="Search..." class="search-input" id="searchInput">
                    <button class="search-icon" onclick="toggleSearch()">
                        <svg viewBox="0 0 24 24">
                            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                        </svg>
                    </button>
                </div>
                <button class="login-btn" onclick="window.location.href='login.php'">Login</button>
            </div>
        </div>
    </nav>

    <!-- News Section -->
    <div class="news-container">
        <div class="section-header">
            <h2>Latest Health News</h2>
            <p>Stay informed with the latest developments in healthcare</p>
        </div>
        
        <div class="news-grid">
            <?php foreach ($news as $article): ?>
            <div class="news-card">
                <div class="news-image">
                    <img src="../<?php echo htmlspecialchars($article['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                </div>
                <div class="news-content">
                    <div class="news-date"><?php echo date('F j, Y', strtotime($article['publication_date'])); ?></div>
                    <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                    <p><?php echo htmlspecialchars($article['summary']); ?></p>
                    <?php if (!empty($article['news_link'])): ?>
                        <a href="<?php echo htmlspecialchars($article['news_link']); ?>" class="read-more">Read More</a>
                    <?php else: ?>
                        <a href="news-detail.php?slug=<?php echo htmlspecialchars($article['slug']); ?>" class="read-more">Read More</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=1">&laquo; First</a>
                <a href="?page=<?php echo $page - 1; ?>">Previous</a>
            <?php else: ?>
                <span class="disabled">&laquo; First</span>
                <span class="disabled">Previous</span>
            <?php endif; ?>

            <?php
            $start_page = max(1, $page - 2);
            $end_page = min($total_pages, $start_page + 4);
            $start_page = max(1, $end_page - 4);

            for ($i = $start_page; $i <= $end_page; $i++):
            ?>
                <?php if ($i == $page): ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next</a>
                <a href="?page=<?php echo $total_pages; ?>">Last &raquo;</a>
            <?php else: ?>
                <span class="disabled">Next</span>
                <span class="disabled">Last &raquo;</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3 class="logo" style="color: azure;">E<span>well</span></h3>
                <p>Your trusted partner in healthcare, dedicated to providing personalized medical services that improve quality of life.</p>
                <div class="social-links">
                    <a href="https://web.facebook.com/DTI.Region12"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#" class="active">News</a></li>
                    <li><a href="#ranks">Ranks</a></li>
                    <li><a href="#locations">Locations</a></li>
                    <li><a href="index.php#feedback">Feedbacks</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Contact Info</h3>
                <ul class="contact-list">
                    <li><i class="fas fa-map-marker-alt"></i> Prime Regional Government Center, Barangay Carpenter Hill, Koronadal City, South Cotabato, Koronadal, Philippines</li>
                    <li><i class="fas fa-phone"></i> (083) 228 9837</li>
                    <li><i class="fas fa-envelope"></i> r12@dti.gov.ph</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Ewell. All rights reserved.</p>
        </div>
    </footer>

    <script src="../js/navbar_seach.js"></script>
    <script src="../js/main.js"></script>
</body>
</html> 