<?php
require_once('../config/database.php');

// Fetch the latest 3 active news articles
$stmt = $pdo->prepare("SELECT * FROM news_articles WHERE status = 'Active' ORDER BY publication_date DESC LIMIT 3");
$stmt->execute();
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Portal</title>
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
            /* Reduced space between logo and Ewell */
            margin-left: 0;
            /* Ensure no extra margin */
            padding-left: 0;
            /* Optional if you had padding */
        }


        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo-img {
            height: 60px;
            /* Adjust as needed */
            width: auto;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }


        /* Optional: make sure nav-links don’t overflow on small screens */
        .nav-links {
            display: flex;
            list-style: none;
            gap: 20px;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="logo-container">
                <img src="image/DTI_w12.png" alt="DTI Logo" class="logo-img">
                <a href="index.php" class="logo">E<span>well</span></a>
            </div>
            <button class="menu-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-links" id="navLinks">
                <li><a href="#" id="home" class="active">Home</a></li>
                <li><a href="#news" id="news-link">News</a></li>
              
                <li><a href="#feedback" id="feedback-link">Feedbacks</a></li>
            </ul>
            <div class="search-container">
                <div class="search-bar" id="searchBar">
                    <input type="text" placeholder="Search..." class="search-input" id="searchInput">
                    <button class="search-icon" onclick="toggleSearch()">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                        </svg>
                    </button>
                </div>
                <button class="login-btn" onclick="window.location.href='login.php'">Login</button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1>DTI REGION 12 HEALTH AND WELLNESS PORTAL</h1>
                <p>Empowering Wellness, Anytime, Anywhere.</p>
                <div class="hero-buttons">
                    <a href="#services" class="btn primary-btn">Our Services</a>
                    <a href="#feedback" class="btn secondary-btn">Leave Feedback</a>
                </div>
            </div>
            <div class="hero-images">
                <div class="image-container main-image">
                    <img src="image/pic/1.jpg" alt="Healthcare Professional">
                </div>
                <div class="image-container top-image">
                    <img src="image/pic/2.jpg" alt="Medical Services">
                </div>
                <div class="image-container bottom-image">
                    <img src="image/pic/3.jpg" alt="Wellness">
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="news" id="news">
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
        <div class="center-button">
            <a href="news.php" class="btn outline-btn">View All News</a>
        </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    
   
    
    </section>

    <!-- Features Section -->
    <section class="features" id="services">
        <div class="section-header">
            <h2> Get help for the following topics</h2>
            <p>Comprehensive care tailored to your individual needs</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="image/1.png" alt="Physical Well-being">
                </div>
                <h3>Physical Well-being</h3>
                <p>Our team is committed to promoting physical well-being through guided fitness programs, health
                    education, and personalized support.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="image/2.png" alt="Financial Well-being">
                </div>
                <h3>Financial Well-being</h3>
                <p>Experienced professionals providing personalized guidance, educational resources, and tools to
                    support your financial well-being and long-term financial goals.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="image/4.png" alt="Emotional & Mental">
                </div>
                <h3>Emotional & Mental Well-being</h3>
                <p>Compassionate support, expert care, and practical resources to help you navigate life's challenges
                    and strengthen your emotional and mental well-being.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <img src="image/5.png" alt="Social Well-being">
                </div>
                <h3>Social Well-being</h3>
                <p>Foster meaningful connections, build a sense of belonging, and engage in activities that strengthen
                    community and social support.
                </p>
            </div>
        </div>
    </section>




    <!-- Feedback Section -->
    <section class="feedback" id="feedback">
        <div class="section-header">
            <h2>Share Your Experience</h2>
            <p>Your feedback helps us continually improve our services. All submissions are completely anonymous.</p>
        </div>
        <div class="feedback-container">
            <div class="feedback-form-container">
                <form class="feedback-form">
                    <div class="form-group">
                        <label for="rating">Rate Your Experience</label>
                        <div class="rating-container">
                            <div class="rating">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2"><i class="fas fa-star"></i></label>
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1"><i class="fas fa-star"></i></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comments">Your Comments</label>
                        <textarea id="comments" name="comments" rows="5"
                            placeholder="Tell us about your experience"></textarea>
                    </div>
                    <button type="submit" class="btn primary-btn">Submit Anonymous Feedback</button>
                </form>
            </div>
            <div class="feedback-image">
                <img src="image/pic/9.png" alt="Feedback">
                <div class="feedback-overlay">
                    <h3>Why Your Feedback Matters</h3>
                    <p>Your trusted partner in e-wellness, committed to delivering personalized digital health solutions
                        that enhance your overall quality of life.</p>
                    <p>Every comment is carefully reviewed by our team and plays a vital role in improving our digital
                        wellness services. To encourage honest and unbiased input, all feedback is collected
                        anonymously.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section about">
                <h3 class="logo" style="color: azure;">E<span>well</span></h3>
                <p>Your trusted partner in healthcare, dedicated to providing personalized medical services that improve
                    quality of life.</p>
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
                    <li><a href="#">Home</a></li>
                    <li><a href="#news">News</a></li>
                    <li><a href="#ranks">Ranks</a></li>
                    <li><a href="#locations">Locations</a></li>
                    <li><a href="#feedback">Feedbacks</a></li>
                </ul>
            </div>
            <div class="footer-section contact">
                <h3>Contact Info</h3>
                <ul class="contact-list">
                    <li><i class="fas fa-map-marker-alt"></i> Prime Regional Government Center, Barangay Carpenter Hill,
                        Koronadal City, South Cotabato, Koronadal, Philippines</li>
                    <li><i class="fas fa-phone"></i> (083) 228 9837</li>
                    <li><i class="fas fa-envelope"></i> r12@dti.gov.ph</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Ewell. All rights reserved.</p>
        </div>
    </footer>

    <script src="js/navbar_seach.js"></script>
    <script src="js/main.js"></script>
</body>

</html>