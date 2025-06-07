<?php
require_once('../config/database.php');

// Get the slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// Fetch the news article
$stmt = $pdo->prepare("SELECT * FROM news_articles WHERE slug = ? AND status = 'Active'");
$stmt->execute([$slug]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

// If article not found, redirect to news page
if (!$article) {
    header('Location: news.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Wellness Portal</title>
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

        .article-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .article-header {
            margin-bottom: 30px;
        }

        .article-title {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #333;
        }

        .article-meta {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .article-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .article-content {
            line-height: 1.8;
            font-size: 1.1em;
            color: #333;
            white-space: pre-wrap;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }

        .back-button i {
            margin-right: 5px;
        }

        .back-button:hover {
            color: #4CAF50;
        }

        .published-by {
            font-style: italic;
            color: #666;
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

    <!-- Article Content -->
    <div class="article-container">
        <a href="index.php#news" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to News
        </a>
        <article class="article-header">
            <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>
            <div class="article-meta">
                <span>Published on <?php echo date('F j, Y', strtotime($article['publication_date'])); ?></span>
                <?php if (!empty($article['author'])): ?>
                    <span class="published-by"> by <?php echo htmlspecialchars($article['author']); ?></span>
                <?php endif; ?>
            </div>
            <?php if (!empty($article['image_url'])): ?>
                <img src="../<?php echo htmlspecialchars($article['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
            <?php endif; ?>
        </article>
        <div class="article-content">
            <?php 
            // Remove excessive backslashes and convert newlines
            $content = stripslashes($article['content']);
            $content = str_replace('\r\n', "\n", $content);
            echo nl2br(htmlspecialchars($content)); 
            ?>
        </div>
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
                    <li><a href="index.php#news">News</a></li>
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